import * as htmlToImage from 'html-to-image';
import { nanoid } from 'nanoid';
import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import 'primereact/resources/primereact.min.css';
import React, { useContext, useEffect, useRef, useState } from 'react';
import ReactFlow, { addEdge, ReactFlowProvider, removeElements } from 'react-flow-renderer';
import { Prompt } from 'react-router-dom';
import { getImpactAreas, getSdgCollections } from '../../services/editor-data';
import {
  createNewTocVersion,
  getFlow,
  getTocData,
  publishToc,
  updateTocEditorData,
} from '../../services/flows';
import { ToastContext } from '../../store';
import { saveAs } from '../../utilities/helpers';
import ChangeNodeTextModal from '../dialogs/ChangeNodeTextModal';
import LeftSidebar from './left-sidebar';
import IniativeLevelMenubar from './menubars/iniative-level-menubar';
import WorkPackageLevelMenubar from './menubars/work-package-level-menubar';
import ActionAreaOutcome from './node-types/ActionAreaOutcome';
import CGIAR from './node-types/CGIAR';
import EndOfInitiativeOutcome from './node-types/EndOfInitiativeOutcome';
import SDGIndicator from './node-types/SDGIndicator';
import WorkPackage from './node-types/WorkPackage';
import WorkPackageOutcome from './node-types/WorkPackageOutcome';
import WorkPackageOutput from './node-types/WorkPackageOutput';
import EditorSidebar from './sidebar';

const nodeTypes = {
  SDGIndicator,
  cgiar: CGIAR,
  actionAreaOutcome: ActionAreaOutcome,
  endOfInitiativeOutcome: EndOfInitiativeOutcome,
  workPackageOutput: WorkPackageOutput,
  workPackageOutcome: WorkPackageOutcome,
  workPackage: WorkPackage,
};

const DiagramEditor = ({ flavour, flowId, tocId, readOnly }) => {
  const [elements, setElements] = useState([]);
  const [usedSDGIndicators, setUsedSDGIndicators] = useState([]);
  const [sidebarVisible, toggleSidebarVisible] = useState(false);
  const [leftSidebarType, setLeftSidebarType] = useState(null);
  const [selectedElement, setSelectedElement] = useState(null);
  const [narrative, setNarrative] = useState('');
  const [comments, setComments] = useState('');
  const [challenge, setChallenge] = useState('');
  const [reviewerComments, setReviewerComments] = useState('');
  const [stakeholderComments, setStakeholderComments] = useState('');
  const [actionAreas, setActionAreas] = useState([]);
  const [changeTextModalOpen, setChangeTextModalOpen] = useState(false);
  const [impactAreas, setImpactAreas] = useState([]);
  const [sdgs, setSdgs] = useState([]);
  const nodesMetadata = useRef({});
  const { setError, setSuccess } = useContext(ToastContext);
  const [published, setPublished] = useState(false);
  const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);
  const [loaded, setLoaded] = useState(false);
  const [role, setRole] = useState(null);
  const [loadingState, setLoadingState] = useState({
    enabled: true,
    message: 'Fetching TOC data...',
  });
  const diagramCanvas = useRef(null);
  const reactFlowInstance = useRef(null);

  let MenuBar = null;

  // TODO: Transfer this somewhere more appropriate.
  const ELEMENT_TYPE_GCIAR_IMPACT_AREA = 'cgiar';
  const ELEMENT_TYPE_SDG_INDICATOR = 'SDGIndicator';

  const getImpactAreaIndicators = (impactAreaCode) => {
    if (!impactAreaCode || impactAreaCode?.length === 0) {
      return [];
    }
    return impactAreas.filter(({ code }) => code === impactAreaCode)?.pop()?.indicators || [];
  };

  const getSDGIndicators = (targetCodes) => {
    const targets = sdgs
      .map((sdg) => [...sdg.targets])
      .flat()
      .filter((t) => targetCodes.includes(t.code));
    return targets.map((t) => [...t.indicators]).flat();
  };

  const getIndicators = (element, targetCodes) => {
    switch (element.type) {
      case ELEMENT_TYPE_GCIAR_IMPACT_AREA:
        return getImpactAreaIndicators(element?.data?.code);
      case ELEMENT_TYPE_SDG_INDICATOR:
        return getSDGIndicators(targetCodes);
      default:
        return [];
    }
  };

  const changeNodeMetadata = (id, metadata) => {
    if (!id) return;
    setHasUnsavedChanges(true);
    nodesMetadata.current[id] = metadata;
  };

  const getNodeMetadata = (id) => nodesMetadata?.current[id];

  const loadTocData = async (tid) => {
    setLoadingState({
      enabled: true,
      message: 'Fetching TOC data...',
    });
    try {
      // Load impact areas, sdgs and action areas first.
      const { data: ia } = await getImpactAreas();
      const { data: t } = await getSdgCollections();
      const { data: flowData, user_role: roles } = await getFlow(flowId);
      setImpactAreas(ia?.impact_areas || []);
      setSdgs(t?.sdgs || []);
      setActionAreas(flowData?.programme?.action_areas || []);
      setRole(roles[1] || null);

      // Load TOC data.
      const { data } = await getTocData(tid);
      setPublished(data?.published || false);
      setLoadingState({ enabled: false });
      toggleSidebarVisible(true);
      return data.toc;
    } catch (e) {
      setLoadingState({ enabled: false });
      setError('Error', 'Failed to fetch TOC data!');
    }
    return null;
  };

  useEffect(() => {
    loadTocData(tocId)
      .then((data) => {
        setElements(data?.elements || []);
        setNarrative(data?.narrative || '');
        setComments(data?.comments || '');
        setChallenge(data?.challenge || '');
        setReviewerComments(data?.reviewerComments || '');
        setStakeholderComments(data?.stakeholderComments || '');
        nodesMetadata.current = data?.nodesMetadata || {};
        const sdgIndicators = data?.elements
          ?.filter((e) => e.type === ELEMENT_TYPE_SDG_INDICATOR)
          .map(({ data: d }) => d.code);
        setUsedSDGIndicators(sdgIndicators || []);
      })
      .then(() => {
        setLeftSidebarType(null);
        setSelectedElement(null);
        setLoaded(true);
      });
  }, [flavour, tocId]); // eslint-disable-line

  useEffect(() => {
    if (loaded) {
      setHasUnsavedChanges(true);
    }
    // eslint-disable-next-line
  }, [narrative, challenge, comments, reviewerComments, stakeholderComments]);

  switch (flavour) {
    case 'initiative-level':
      MenuBar = IniativeLevelMenubar;
      break;
    case 'work-package-level':
      MenuBar = WorkPackageLevelMenubar;
      break;
    default:
      MenuBar = IniativeLevelMenubar;
  }

  const toggleLeftSidebar = (type) => {
    if (type === leftSidebarType) {
      setLeftSidebarType(null);
    } else {
      setLeftSidebarType(type);
    }
  };

  const onElementsRemove = (elementsToRemove) => {
    setHasUnsavedChanges(true);
    setElements((els) => removeElements(elementsToRemove, els));
    // Cleanup the metadata array from removed elements.
    elementsToRemove.forEach(({ id, data, type }) => {
      if (nodesMetadata.current[id]) {
        delete nodesMetadata.current[id];
      }
      if (type === ELEMENT_TYPE_SDG_INDICATOR) {
        setUsedSDGIndicators(usedSDGIndicators.filter((code) => code !== data.code));
      }
    });
    setSelectedElement(null);
  };

  const onConnect = (params) => {
    const edgeId = nanoid();
    setElements((els) => addEdge({ ...params, id: edgeId }, els));
    setSelectedElement(null);
  };

  const onElementClick = (event, element) => {
    setSelectedElement(null);
    if (element) {
      setSelectedElement(element);
    }
  };

  // TODO: Maybe need to save the node's new position.
  const onNodeDragStop = (event, element) => {
    setSelectedElement(element);
    editSelectedNode(() => ({ ...element }));
  };
  const onNodeDragStart = (event, element) => setSelectedElement(element);

  const onNodeDoubleClick = (event, { type }) => {
    const types = [
      'actionAreaOutcome',
      'endOfInitiativeOutcome',
      'workPackage',
      'workPackageOutput',
      'workPackageOutcome',
    ];
    if (types.includes(type)) {
      setChangeTextModalOpen(true);
    }
  };

  const getCurrentTocData = () => ({
    narrative,
    comments,
    challenge,
    reviewerComments,
    stakeholderComments,
    elements,
    nodesMetadata: nodesMetadata.current,
  });

  const saveNewVersion = async () => {
    try {
      await createNewTocVersion(tocId, getCurrentTocData());
      setSuccess('Success', 'A new version of the current TOC was created successfully!');
    } catch (error) {
      setError('Error', 'Failed to create a new TOC version!');
    }
  };

  const saveAsPng = async () => {
    toggleSidebarVisible(false);
    await new Promise((r) => setTimeout(r, 50));
    reactFlowInstance.current.fitView();
    reactFlowInstance.current.zoomTo(0.75);
    await new Promise((r) => setTimeout(r, 50));
    const dataUrl = await htmlToImage.toPng(document.getElementById('react-flow-editor'));
    saveAs(dataUrl, `${nanoid()}.png`);
  };

  const publish = async () => {
    setLoadingState({ enabled: true, message: 'Publishing TOC...' });
    try {
      await new Promise((r) => setTimeout(r, 50));
      reactFlowInstance.current.fitView();
      reactFlowInstance.current.zoomTo(0.75);
      await new Promise((r) => setTimeout(r, 50));
      const image = await htmlToImage.toPng(document.getElementById('react-flow-editor'));
      await publishToc(tocId, narrative, image);
      setSuccess('Success', 'TOC was published successfully!');
      setPublished(true);
    } catch (error) {
      setError('Error', 'Failed to publish TOC!');
    }
    setLoadingState({ enabled: false });
  };

  const saveFlow = async () => {
    if (readOnly) {
      // Read-only mode, changes don't persist.
      return;
    }
    setLoadingState({ enabled: true, message: 'Saving TOC...' });
    try {
      await updateTocEditorData(tocId, getCurrentTocData());
      setSuccess('Success', 'The current TOC was saved successfully!');
      setHasUnsavedChanges(false);
      if (published) {
        await publish();
      }
    } catch (e) {
      setError('Error', 'Failed to save TOC!');
    }
    setLoadingState({ enabled: false });
  };

  const randomBetween = (min, max) => Math.floor(Math.random() * (max - min + 1) + min);

  const addNode = (type, nodeData) => {
    setHasUnsavedChanges(true);
    if (type === ELEMENT_TYPE_SDG_INDICATOR) {
      setUsedSDGIndicators(
        usedSDGIndicators.filter((code) => code !== nodeData.code).concat(nodeData.code),
      );
    }
    setElements([
      ...elements,
      {
        id: `node-e${nanoid()}`,
        type,
        data: { ...nodeData },
        position: { x: randomBetween(150, 600), y: randomBetween(50, 400) },
      },
    ]);
  };

  const editSelectedNode = (cb) => {
    if (!selectedElement) return;
    const editedElement = cb(selectedElement);
    const elementIndex = elements.findIndex((e) => e.id === editedElement.id);
    if (elementIndex < 0) return;
    const allElements = [...elements];
    allElements[elementIndex] = { ...editedElement };
    setElements(allElements);
    setSelectedElement(editedElement);
  };

  const deleteSelectedNode = () => {
    if (selectedElement) {
      onElementsRemove([selectedElement]);
    }
  };

  return (
    <>
      <Prompt
        when={hasUnsavedChanges}
        message="You have unsaved changes, are you sure you want to leave?"
      />
      <ReactFlowProvider>
        <div
          style={{
            height: '100%',
            width: '100%',
            position: 'relative',
          }}
        >
          {readOnly === false && (
            <MenuBar
              saveFlow={saveFlow}
              toggleLeftSidebar={toggleLeftSidebar}
              saveAsPng={saveAsPng}
              saveNewVersion={saveNewVersion}
              sidebarVisible={sidebarVisible}
              toggleSidebarVisible={toggleSidebarVisible}
              addNode={addNode}
              editSelectedNode={editSelectedNode}
              deleteSelectedNode={deleteSelectedNode}
              fitView={() => {
                reactFlowInstance.current.fitView({ padding: 0.15, includeHiddenNodes: true });
                reactFlowInstance.current.zoomTo(0.5);
              }}
              selectedElement={selectedElement}
              publish={publish}
            />
          )}
          {loadingState.enabled && (
            <div
              className="p-d-flex p-jc-center p-ai-center"
              style={{
                width: '100%',
                height: '100vh',
                zIndex: 99999999,
                background: 'rgba(0,0,0,0.4)',
                position: 'absolute',
                top: 0,
                left: 0,
              }}
            >
              <div className="p-text-center">
                <h4 className="p-mb-4">{loadingState.message}</h4>
                <i className="pi pi-spin pi-spinner" style={{ fontSize: '2.5em' }} />
              </div>
            </div>
          )}
          <LeftSidebar
            usedSDGIndicators={usedSDGIndicators}
            addNode={addNode}
            type={leftSidebarType}
            actionAreas={actionAreas}
          />
          <div className="p-d-flex p-ai-start">
            <div
              className="p-order-1"
              style={{ width: sidebarVisible ? '75%' : '100%' }}
              ref={diagramCanvas}
            >
              <ReactFlow
                id="react-flow-editor"
                ref={reactFlowInstance}
                onLoad={(instance) => {
                  reactFlowInstance.current = instance;
                }}
                style={{ width: '100%', height: '100vh' }}
                elements={elements}
                nodeTypes={nodeTypes}
                onElementsRemove={onElementsRemove}
                onConnect={onConnect}
                onElementClick={onElementClick}
                // onNodeDrag={onNodeDrag}
                onNodeDragStart={onNodeDragStart}
                onNodeDragStop={onNodeDragStop}
                onPaneClick={() => setSelectedElement(null)}
                onNodeDoubleClick={onNodeDoubleClick}
                connectionMode="loose"
                connectionLineStyle={{ strokeWidth: 2 }}
              />
            </div>
            <div
              className="p-order-2 diagram-editor-sidebar p-mt-1"
              style={{
                width: '25%',
                minWidth: '350px',
                display: sidebarVisible ? 'block' : 'none',
              }}
            >
              <EditorSidebar
                role={role}
                selectedElement={selectedElement}
                narrative={narrative}
                setNarrative={setNarrative}
                comments={comments}
                setComments={setComments}
                reviewerComments={reviewerComments}
                setReviewerComments={setReviewerComments}
                stakeholderComments={stakeholderComments}
                setStakeholderComments={setStakeholderComments}
                challenge={challenge}
                setChallenge={setChallenge}
                getIndicators={getIndicators}
                getNodeMetadata={getNodeMetadata}
                changeNodeMetadata={changeNodeMetadata}
                readOnly={readOnly}
              />
            </div>
          </div>
          <ChangeNodeTextModal
            open={changeTextModalOpen}
            setOpen={setChangeTextModalOpen}
            selectedElement={selectedElement}
            elements={elements}
            setElements={setElements}
          />
        </div>
      </ReactFlowProvider>
    </>
  );
};

export default DiagramEditor;
