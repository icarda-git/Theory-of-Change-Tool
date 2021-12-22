import { nanoid } from 'nanoid';
import React, { useContext, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import TocFlowInfo from '../components/forms/TocFlowInfo';
import TocFlowTeamComposition from '../components/forms/TocFlowTeamComposition';
import TocLevels from '../components/forms/TocLevels';
import Tocs from '../components/forms/Tocs';
import Loading from '../components/Loading';
import { getActionAreaOutcomes } from '../services/editor-data';
import { getFlow, getTocs, getUserFlows, updateFlow } from '../services/flows';
import { ToastContext, UserContext } from '../store';
import {
  GetCGIARInitiativeFromProgrameId,
  getProgrammeTypeId,
  GetProjectOrProposal,
  getTocType,
  validate,
} from '../utilities/helpers';

const TocOverview = () => {
  // STEP 2
  const [isLoading, setIsLoading] = useState(false);
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [actionAreas, setActionAreas] = useState([]);
  const [selectedActionAreas, setSelectedActionAreas] = useState([]);
  const [funders, setFunders] = useState([]);
  const [partners, setPartners] = useState([]);
  const [sustainableDevelopmentGoals, setSustainableDevelopmentGoals] = useState([]);
  const [cgiarInitiative, setCgiarInitiative] = useState(false);
  const [participants, setParticipants] = useState([]);
  const [pendingRequests, setPendingRequests] = useState([]);
  const [initiativeLevel, setInitiativeLevel] = useState(true);
  const [workPackageLevel, setWorkPackageLevel] = useState(false);
  const [participatoryDevelopment, setParticipatoryDevelopment] = useState(false);
  const [participatoryDevelopmentURL, setParticipatoryDevelopmentURL] = useState('');
  const [mindmap, setMindMap] = useState(false);
  const [level, setLevel] = useState('');
  const [workPackages, setWorkPackages] = useState([]);
  const [type, setType] = useState('');
  const [tocs, setTocs] = useState([]);
  const { flowId } = useParams();
  const { setError, setSuccess } = useContext(ToastContext);
  const { setUser } = useContext(UserContext);

  const updateTocOverview = async () => {
    setIsLoading(true);
    try {
      validate({ workPackageLevel, workPackages, cgiarInitiative, type, selectedActionAreas });
    } catch (error) {
      setIsLoading(false);
      setError('Error', error.message);
      return;
    }
    try {
      await updateFlow(flowId, {
        programme: {
          programme_id: 0,
          title,
          description,
          type: getProgrammeTypeId(cgiarInitiative, type === 'project', type === 'proposal'),
          action_areas: selectedActionAreas.map((v) => Number(v)),
          donors: funders.map(({ orgId, orgName }) => ({ org_id: orgId, org_name: orgName })),
          partners: partners.map(({ orgId, orgName }) => ({ org_id: orgId, org_name: orgName })),
          sdgs: sustainableDevelopmentGoals.map((v) => Number(v)),
        },
        pdb: {
          status: participatoryDevelopment ? 'enabled' : 'disabled',
          pdb_link: participatoryDevelopmentURL,
        },
        team_members: participants.map(({ email, role }) => ({ email, role })),
        work_packages: workPackages.map(({ toc_id: id, number, title: tocTitle }) => {
          if (id) {
            return { id, number, title: tocTitle };
          }
          return { number, title: tocTitle };
        }),
      });
      setSuccess('The flow details were updated!');
      await loadFlowWithTocsAndActionAreas();
      const { data } = await getUserFlows();
      setUser({ flows: data.flows });
    } catch (error) {
      setError(error.message);
    }
    setIsLoading(false);
  };

  const loadFlowWithTocsAndActionAreas = async () => {
    setIsLoading(true);
    try {
      const { data: flowActionAreas } = await getActionAreaOutcomes();
      setActionAreas(flowActionAreas.action_areas);
      const { data: flow, toc_details: wp } = await getFlow(flowId);
      const flowTocs = await getTocs(flowId);
      if (flow) {
        setParticipants(
          flow.team_members.map(({ email, role }) => ({ id: nanoid(), email, role })),
        );
        setParticipatoryDevelopment(flow?.pdb?.status === 'enabled');
        setParticipatoryDevelopmentURL(flow?.pdb?.pdb_link || '');
        setTitle(flow?.programme?.title || '');
        setDescription(flow?.programme?.description || '');
        setSelectedActionAreas(flow?.programme?.action_areas || []);
        setFunders(
          flow.programme.donors.map(({ org_id: orgId, org_name: orgName }) => ({
            id: nanoid(),
            orgId,
            orgName,
          })),
        );
        setPartners(
          flow.programme.partners.map(({ org_id: orgId, org_name: orgName }) => ({
            id: nanoid(),
            orgId,
            orgName,
          })),
        );
        setSustainableDevelopmentGoals(flow.programme.sdgs);
        setInitiativeLevel(flow.initiative_level);
        setWorkPackageLevel(flow.workpackage_level);
        setCgiarInitiative(GetCGIARInitiativeFromProgrameId(flow.programme.type));
        setType(GetProjectOrProposal(flow.programme.type));
        setWorkPackages(wp.map((w) => ({ ...w, id: w.toc_id })));
        setTocs(
          flowTocs
            ?.sort((a, b) => Number(a.number) - Number(b.number))
            ?.map((t) => ({ ...t, type: getTocType(t?.toc_type) })) || [],
        );
      }
    } catch (error) {
      setError('Error', error.message);
    }
    setIsLoading(false);
  };

  useEffect(() => {
    setUser({ currentFlowId: flowId });
    loadFlowWithTocsAndActionAreas();
  }, []); // eslint-disable-line

  useEffect(() => {
    loadFlowWithTocsAndActionAreas();
  }, [flowId]); // eslint-disable-line

  useEffect(() => {
    if (cgiarInitiative) {
      setInitiativeLevel(true);
      setWorkPackageLevel(true);
    }
  }, [cgiarInitiative]);

  const toggleSDGIndicator = (id) => {
    sustainableDevelopmentGoals.includes(id) ? removeSdgIndicator(id) : addSdgIndicator(id);
  };

  const toggleSelectedActionArea = (id) => {
    selectedActionAreas.includes(id) ? removeSelectedActionArea(id) : addSelectedActionArea(id);
  };

  const addSdgIndicator = (id) => {
    setSustainableDevelopmentGoals([...sustainableDevelopmentGoals, id]);
  };

  const addSelectedActionArea = (id) => {
    setSelectedActionAreas([...selectedActionAreas, id]);
  };

  const removeSdgIndicator = (id) => {
    setSustainableDevelopmentGoals(sustainableDevelopmentGoals.filter((item) => item !== id));
  };

  const removeSelectedActionArea = (id) => {
    setSelectedActionAreas(selectedActionAreas.filter((item) => item !== id));
  };

  return isLoading ? (
    <Loading />
  ) : (
    <>
      <TocFlowInfo
        title={title}
        setTitle={setTitle}
        description={description}
        setDescription={setDescription}
        actionAreas={actionAreas}
        selectedActionAreas={selectedActionAreas}
        funders={funders}
        setFunders={setFunders}
        partners={partners}
        setPartners={setPartners}
        sustainableDevelopmentGoals={sustainableDevelopmentGoals}
        toggleSDGIndicator={toggleSDGIndicator}
        toggleSelectedActionArea={toggleSelectedActionArea}
        cgiarInitiative={cgiarInitiative}
        setCgiarInitiative={setCgiarInitiative}
        type={type}
        setType={setType}
        showDescription
        mode="edit"
      />
      <TocLevels
        cgiarInitiative={cgiarInitiative}
        initiativeLevel={initiativeLevel}
        setInitiativeLevel={setInitiativeLevel}
        workPackageLevel={workPackageLevel}
        setWorkPackageLevel={setWorkPackageLevel}
        workPackages={workPackages}
        setWorkPackages={setWorkPackages}
        edit
      />
      <Tocs tocs={tocs} />
      <TocFlowTeamComposition
        participants={participants}
        setParticipants={setParticipants}
        pendingRequests={pendingRequests}
        setPendingRequests={setPendingRequests}
        participatoryDevelopment={participatoryDevelopment}
        setParticipatoryDevelopment={setParticipatoryDevelopment}
        participatoryDevelopmentURL={participatoryDevelopmentURL}
        setParticipatoryDevelopmentURL={setParticipatoryDevelopmentURL}
        mindmap={mindmap}
        setMindMap={setMindMap}
        level={level}
        setLevel={setLevel}
        onSubmit={updateTocOverview}
        id={flowId}
      />
    </>
  );
};

export default TocOverview;
