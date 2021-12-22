import { nanoid } from 'nanoid';
import { Button } from 'primereact/button';
import { Card } from 'primereact/card';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import { ProgressBar } from 'primereact/progressbar';
import React, { useContext, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { NavLink } from 'react-router-dom';
import { getUserFlows } from '../services/flows';
import { acceptInvite, getUserInvites, rejectInvite } from '../services/invites';
import { ToastContext, UserContext } from '../store';

const getProgressBarColor = (progress, role) => {
  if (progress === 100 && role === 'stakeholder') {
    return '#C47C30';
  }
  if (progress === 100 && role === 'reviewer') {
    return '#77AF52';
  }
  return '#2196F3';
};

const getInviteText = (name, email, project, role) =>
  `You have been invited by ${name} (${email}) to join the ToC Flow creation team for ${project} as a ${role}.`;

const ProjectCardHeader = ({ project, progress, role }) => (
  <>
    <div className="p-d-flex p-jc-between">
      <div className="p-pl-3 p-pt-2">
        <div className="p-card-title">{project}</div>
      </div>
      <ProgressBar
        color={getProgressBarColor(progress, role)}
        showValue={false}
        value={progress}
        className="w-50 p-mr-3 p-mt-3"
      />
    </div>
  </>
);

const Dashboard = () => {
  const { t } = useTranslation();
  const { flows } = useContext(UserContext);
  const { setError, setSuccess } = useContext(ToastContext);
  const { setUser } = useContext(UserContext);
  const [invites, setInvites] = useState([]);

  const removeInvite = (id) => {
    setInvites(invites.filter(({ id: inviteId }) => inviteId !== id));
  };

  const getInvites = async () => {
    try {
      const { data } = await getUserInvites();
      setInvites(
        data.map(
          ({
            invitation_id: id,
            inviter_email: email,
            inviter_name: name,
            team_name: projectName,
            user_assigned_role: roleName,
          }) => ({
            id,
            text: getInviteText(name, email, projectName, roleName),
          }),
        ),
      );
    } catch (error) {
      setError(error.message);
    }
  };

  const accept = async (id) => {
    try {
      await acceptInvite(id);
      setSuccess('The invite was accepted!');
      removeInvite(id);
      const { data } = await getUserFlows();
      setUser({ flows: data.flows });
    } catch (error) {
      setError(error.message);
    }
  };

  const reject = async (id) => {
    try {
      await rejectInvite(id);
      setSuccess('The invite was rejected!');
      removeInvite(id);
    } catch (error) {
      setError(error.message);
    }
  };

  const actionsTemplate = ({ id }) => (
    <span>
      <Button
        onClick={() => accept(id)}
        icon="pi pi-check"
        className="p-mr-3"
        label={t('ACCEPT')}
      />
      <Button
        onClick={() => reject(id)}
        icon="pi pi-times"
        className="p-button-danger"
        label={t('REJECT')}
      />
    </span>
  );

  useEffect(() => {
    getInvites();
  }, []); // eslint-disable-line

  return (
    <div className="layout-dashboard">
      <div className="p-grid">
        <div className="p-col-12">
          <div className="p-d-flex p-ai-center p-jc-start">
            <h3 className="p-mb-0 p-text-capitalize">{t('MY_TOC_FLOWS')}</h3>
            <NavLink to="/new-toc-flow">
              <Button icon="pi pi-plus" className="p-ml-3 p-button-rounded" />
            </NavLink>
          </div>
        </div>
        <div className="p-col-12">
          {flows.length > 0 &&
            flows.map(({ title, id, role }) => (
              <div key={nanoid()} className="p-grid">
                <div className="p-col-12 p-md-8">
                  <Card
                    header={() => <ProjectCardHeader project={title} progress={100} />}
                    footer={() => (
                      <div className="p-d-flex p-jc-between">
                        <h4 className="p-text-capitalize">{role}</h4>
                        <div>
                          <Button
                            className="p-mr-3 p-button-success"
                            icon="pi pi-download"
                            label={t('EXPORT')}
                          />
                          <NavLink to={`/flows/${id}/overview`}>
                            <Button icon="pi pi-folder-open" label={t('OPEN')} />
                          </NavLink>
                        </div>
                      </div>
                    )}
                  />
                </div>
              </div>
            ))}
          {flows.length === 0 && <div className="p-col-12 p-md-8">No flows were found.</div>}
        </div>
        <div className="p-col-12 p-md-8">
          <h3 className="p-mb-0 p-text-capitalize">{t('PENDING_INVITATIONS')}</h3>
          <DataTable emptyMessage={t('NO_INVITATIONS_FOUND')} value={invites}>
            <Column field="text" />
            <Column field="actions" body={actionsTemplate} className="p-text-right" />
          </DataTable>
        </div>
        <div className="p-col-12">&nbsp;</div>
      </div>
    </div>
  );
};

export default Dashboard;
