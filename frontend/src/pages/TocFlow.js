import React, { useContext, useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom';
import TocFlowInfo from '../components/forms/TocFlowInfo';
import TocFlowTeamComposition from '../components/forms/TocFlowTeamComposition';
import TocLevels from '../components/forms/TocLevels';
import { getActionAreaOutcomes } from '../services/editor-data';
import { createFlow, getUserFlows } from '../services/flows';
import { ToastContext, UserContext } from '../store';
import { getProgrammeTypeId, validate } from '../utilities/helpers';

const TocFlow = ({ flow }) => {
  // Context
  const { setError, setSuccess } = useContext(ToastContext);
  const { setUser } = useContext(UserContext);

  // State
  const [title, setTitle] = useState(flow?.title || '');
  const [description, setDescription] = useState(flow?.description || '');
  const [actionAreas, setActionAreas] = useState([]);
  const [selectedActionAreas, setSelectedActionAreas] = useState([]);
  const [funders, setFunders] = useState(flow?.donors || []);
  const [partners, setPartners] = useState(flow?.partners || []);
  const [sustainableDevelopmentGoals, setSustainableDevelopmentGoals] = useState([]);
  const [cgiarInitiative, setcgiarInitiative] = useState(true);
  const [participants, setParticipants] = useState([]);
  const [pendingRequests, setPendingRequests] = useState([]);
  const [initiativeLevel, setInitiativeLevel] = useState(true);
  const [workPackageLevel, setWorkPackageLevel] = useState(false);
  const [participatoryDevelopment, setParticipatoryDevelopment] = useState(false);
  const [participatoryDevelopmentURL, setParticipatoryDevelopmentURL] = useState('');
  const [workPackages, setWorkPackages] = useState([]);
  const [mindmap, setMindMap] = useState(false);
  const [level, setLevel] = useState('');
  const [type, setType] = useState('');
  const history = useHistory();

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

  useEffect(() => {
    getActionAreaOutcomes().then(({ data }) => {
      setActionAreas(data?.action_areas || []);
    });
  }, []);

  useEffect(() => {
    if (cgiarInitiative) {
      setInitiativeLevel(true);
      setWorkPackageLevel(true);
    }
  }, [cgiarInitiative]);

  const handleError = (e) => {
    let error = e?.message;
    const statusCode = e?.response?.status;
    error =
      statusCode === 422
        ? e?.response?.data?.data?.error[Object.keys(e?.response?.data?.data?.error)[0]][0]
        : 'Something went wrong!';
    setError('Error', error);
  };

  const createNewFlow = async () => {
    try {
      validate({ workPackageLevel, workPackages, cgiarInitiative, type, selectedActionAreas });
    } catch (error) {
      setError('Error', error.message);
      return;
    }
    try {
      await createFlow({
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
        initiative_level: initiativeLevel,
        workpackage_level: workPackageLevel,
        work_packages: workPackages.map(({ number, title: tocTitle }) => ({
          number,
          title: tocTitle,
        })),
      });
      const { data } = await getUserFlows();
      setUser({ flows: data.flows });
      setSuccess(
        'The flow has been created successfully and it is pending approval by an administrator!',
      );
      history.push('/');
    } catch (error) {
      handleError(error);
    }
  };

  return (
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
        setCgiarInitiative={setcgiarInitiative}
        type={type}
        setType={setType}
        showDescription
        mode="add"
      />
      <TocLevels
        cgiarInitiative={cgiarInitiative}
        initiativeLevel={initiativeLevel}
        setInitiativeLevel={setInitiativeLevel}
        workPackageLevel={workPackageLevel}
        setWorkPackageLevel={setWorkPackageLevel}
        workPackages={workPackages}
        setWorkPackages={setWorkPackages}
      />
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
        onSubmit={createNewFlow}
        showAll={false}
        endFlow
      />
    </>
  );
};

export default TocFlow;
