import { Accordion, AccordionTab } from 'primereact/accordion';
import React, { useEffect, useLayoutEffect, useState } from 'react';
import { isEdge, isNode } from 'react-flow-renderer';
import { useTranslation } from 'react-i18next';
import {
  ROLE_COLEADER,
  ROLE_LEADER,
  ROLE_MEMBER,
  ROLE_REVIEWER,
  ROLE_STAKEHOLDER,
} from '../../utilities/roles';
import Actions from './right-sidebar-components/Actions';
import Challenge from './right-sidebar-components/Challenge';
import Comments from './right-sidebar-components/Comments';
import GenderDimension from './right-sidebar-components/GenderBalance';
import Indicators from './right-sidebar-components/Indicators';
import Narrative from './right-sidebar-components/Narrative';
import ResponsibleEntities from './right-sidebar-components/ResponsibleEntities';
import Targets from './right-sidebar-components/Targets';

// The available accordion tabs.
const NARRATIVE_TAB = 0;
const CHALLENGE_TAB = 1;
const RESPONSIBLE_ENTITIES_TAB = 2;
const TARGETS_TAB = 3;
const INDICATORS_TAB = 4;
const GENDER_DIMENSION_TAB = 6;
const ACTIONS_TAB = 5;
const COMMENTS_TAB = 7;
const COMMENTS_REVIEWER_TAB = 8;
const COMMENTS_STAKEHOLDER_TAB = 9;

const getIndicatorsMode = (selectedElement) => {
  if (
    ['workPackageOutput', 'workPackageOutcome', 'endOfInitiativeOutcome'].includes(
      selectedElement?.type,
    )
  ) {
    return 'edit';
  }
  return 'select';
};

const shouldTabExist = (tabName, selectedElement) => {
  if (selectedElement === null) {
    switch (tabName) {
      case 'narrative':
      case 'challenge':
        return true;
      default:
        return false;
    }
  }

  switch (tabName) {
    case 'narrative':
      return true;
    case 'responsible-entities':
      return (
        isNode(selectedElement) &&
        (selectedElement.type === 'workPackageOutput' ||
          selectedElement.type === 'workPackageOutcome')
      );
    case 'targets':
      return isNode(selectedElement) && selectedElement.type === 'SDGIndicator';
    case 'indicators':
      return (
        isNode(selectedElement) &&
        (selectedElement.type === 'SDGIndicator' ||
          selectedElement.type === 'cgiar' ||
          selectedElement.type === 'workPackageOutput' ||
          selectedElement.type === 'workPackageOutcome' ||
          selectedElement.type === 'endOfInitiativeOutcome')
      );
    case 'genderDimension':
      return (
        isNode(selectedElement) &&
        (selectedElement.type === 'workPackageOutput' ||
          selectedElement.type === 'workPackageOutcome') &&
        selectedElement.data.hasgenderDimension
      );
    case 'actions':
      return isEdge(selectedElement);
    default:
      return false;
  }
};

const getActiveTabs = (selectedElement) => {
  if (selectedElement && isNode(selectedElement)) {
    if (selectedElement.type === 'SDGIndicator') {
      return [
        NARRATIVE_TAB,
        COMMENTS_TAB,
        TARGETS_TAB,
        INDICATORS_TAB,
        COMMENTS_REVIEWER_TAB,
        COMMENTS_STAKEHOLDER_TAB,
      ];
    }
    if (selectedElement.type === 'cgiar') {
      return [
        NARRATIVE_TAB,
        COMMENTS_TAB,
        INDICATORS_TAB,
        COMMENTS_REVIEWER_TAB,
        COMMENTS_STAKEHOLDER_TAB,
      ];
    }
    if (
      selectedElement.type === 'workPackageOutput' ||
      selectedElement.type === 'workPackageOutcome' ||
      selectedElement.type === 'endOfInitiativeOutcome'
    ) {
      return [
        NARRATIVE_TAB,
        COMMENTS_TAB,
        INDICATORS_TAB,
        RESPONSIBLE_ENTITIES_TAB,
        GENDER_DIMENSION_TAB,
        COMMENTS_REVIEWER_TAB,
        COMMENTS_STAKEHOLDER_TAB,
      ];
    }
  } else if (selectedElement && isEdge(selectedElement)) {
    return [
      NARRATIVE_TAB,
      COMMENTS_TAB,
      ACTIONS_TAB,
      COMMENTS_REVIEWER_TAB,
      COMMENTS_STAKEHOLDER_TAB,
    ];
  }
  return [
    NARRATIVE_TAB,
    CHALLENGE_TAB,
    COMMENTS_TAB,
    COMMENTS_REVIEWER_TAB,
    COMMENTS_STAKEHOLDER_TAB,
  ];
};

const EditorSidebar = ({
  role,
  selectedElement,
  narrative,
  setNarrative,
  comments,
  setComments,
  reviewerComments,
  setReviewerComments,
  stakeholderComments,
  setStakeholderComments,
  challenge,
  setChallenge,
  getIndicators,
  getNodeMetadata,
  changeNodeMetadata,
  readOnly,
}) => {
  const { t } = useTranslation();
  const [activeIndexes, setActiveIndexes] = useState([0]);
  const [narrativeHeaderText, setNarrativeHeaderText] = useState(t('NARRATIVE'));
  const [actionsHeaderText, setActionsHeaderText] = useState(t('ACTIONS'));
  const defaults = {
    narrative: '',
    challenge: '',
    comments: '',
    reviewerComments: '',
    stakeholderComments: '',
    genderDimension: '',
    entities: [],
    indicators: [],
  };

  const openSection = (index) => {
    setActiveIndexes([...activeIndexes, index]);
  };

  const closeSection = (index) => {
    setActiveIndexes(activeIndexes.filter((value) => value !== index));
  };

  const setData = (id, key, data) => {
    const previousMetadata = getNodeMetadata(id);
    changeNodeMetadata(id, { ...previousMetadata, [key]: data });
    // TODO: Fix this hack.
    if (key === 'targets') {
      setActiveIndexes(getActiveTabs(selectedElement));
    }
  };

  const getAvailableTargets = (element) => element?.data?.targets || [];
  const getSelectedTargetCodes = (element) => getNodeMetadata(element?.id)?.targets || [];
  const getAvailableIndicators = (element) =>
    getIndicators(element, getSelectedTargetCodes(element));

  useEffect(() => {
    if (selectedElement) {
      const nodeMetadata = getNodeMetadata(selectedElement?.id);
      changeNodeMetadata(selectedElement?.id, nodeMetadata || defaults);
      if (isEdge(selectedElement)) {
        setNarrativeHeaderText(t('CHANGE_PROCESS'));
        setActionsHeaderText(t('ASSUMPTIONS_CAUSAL_LOGIC'));
      } else if (selectedElement?.type === 'SDGIndicator') {
        setNarrativeHeaderText(t('CONTRIBUTION'));
      } else {
        setNarrativeHeaderText(t('NARRATIVE'));
        setActionsHeaderText(t('ACTIONS'));
      }
    } else {
      setNarrativeHeaderText(t('NARRATIVE'));
      setActionsHeaderText(t('ACTIONS'));
    }
  }, [selectedElement]); // eslint-disable-line

  useLayoutEffect(() => {
    setActiveIndexes(getActiveTabs(selectedElement));
  }, [selectedElement]);

  const isReviewer = () => [ROLE_REVIEWER].includes(role);
  const isStakeholder = () => [ROLE_STAKEHOLDER].includes(role);
  const canComment = () => [ROLE_LEADER, ROLE_COLEADER, ROLE_MEMBER].includes(role);

  return (
    <div className="p-pb-6">
      <Accordion
        multiple
        activeIndex={activeIndexes}
        onTabOpen={({ index }) => openSection(index)}
        onTabClose={({ index }) => closeSection(index)}
        onTabChange={({ index }) => setActiveIndexes(index)}
      >
        <AccordionTab header={narrativeHeaderText}>
          <Narrative
            element={selectedElement}
            activeMetadata={getNodeMetadata(selectedElement?.id)}
            setData={setData}
            narrative={narrative}
            setNarrative={setNarrative}
            readOnly={readOnly}
          />
        </AccordionTab>
        {shouldTabExist('challenge', selectedElement) && (
          <AccordionTab header={t('CHALLENGE')}>
            <Challenge
              element={selectedElement}
              activeMetadata={getNodeMetadata(selectedElement?.id)}
              setData={setData}
              challenge={challenge}
              setChallenge={setChallenge}
              readOnly={readOnly}
            />
          </AccordionTab>
        )}
        {shouldTabExist('responsible-entities', selectedElement) && (
          <AccordionTab header={t('RESPONSIBLE_ENTITIES')}>
            <ResponsibleEntities
              element={selectedElement}
              activeMetadata={getNodeMetadata(selectedElement?.id)}
              setData={setData}
              readOnly={readOnly}
            />
          </AccordionTab>
        )}
        {shouldTabExist('targets', selectedElement) && (
          <AccordionTab header={t('TARGETS')}>
            <Targets
              element={selectedElement}
              activeMetadata={getNodeMetadata(selectedElement?.id)}
              setData={setData}
              availableTargets={getAvailableTargets(selectedElement)}
              readOnly={readOnly}
            />
          </AccordionTab>
        )}
        {shouldTabExist('indicators', selectedElement) && (
          <AccordionTab header={t('INDICATORS')}>
            <Indicators
              element={selectedElement}
              activeMetadata={getNodeMetadata(selectedElement?.id)}
              setData={setData}
              availableIndicators={getAvailableIndicators(selectedElement)}
              readOnly={readOnly}
              mode={getIndicatorsMode(selectedElement)}
            />
          </AccordionTab>
        )}
        {shouldTabExist('actions', selectedElement) && (
          <AccordionTab header={actionsHeaderText}>
            <Actions
              element={selectedElement}
              activeMetadata={getNodeMetadata(selectedElement?.id)}
              setData={setData}
              readOnly={readOnly}
            />
          </AccordionTab>
        )}
        {shouldTabExist('genderDimension', selectedElement) && (
          <AccordionTab header={t('GENDER_DIMENSION')}>
            <GenderDimension
              element={selectedElement}
              activeMetadata={getNodeMetadata(selectedElement?.id)}
              setData={setData}
              readOnly={readOnly}
            />
          </AccordionTab>
        )}
        <AccordionTab header={t('COMMENTS')}>
          <Comments
            readOnly={readOnly || !canComment()}
            element={selectedElement}
            activeMetadata={getNodeMetadata(selectedElement?.id)}
            setData={setData}
            comments={comments}
            setComments={setComments}
            commentsField="comments"
          />
        </AccordionTab>
        <AccordionTab header={t('REVIEWER_COMMENTS')}>
          <Comments
            readOnly={readOnly || !isReviewer()}
            element={selectedElement}
            activeMetadata={getNodeMetadata(selectedElement?.id)}
            setData={setData}
            comments={reviewerComments}
            setComments={setReviewerComments}
            commentsField="reviewerComments"
          />
        </AccordionTab>
        <AccordionTab header={t('STAKEHOLDER_COMMENTS')}>
          <Comments
            readOnly={readOnly || !isStakeholder()}
            element={selectedElement}
            activeMetadata={getNodeMetadata(selectedElement?.id)}
            setData={setData}
            comments={stakeholderComments}
            setComments={setStakeholderComments}
            commentsField="stakeholderComments"
          />
        </AccordionTab>
      </Accordion>
    </div>
  );
};

export default EditorSidebar;
