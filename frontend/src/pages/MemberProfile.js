import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import 'primereact/resources/primereact.min.css';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { NavLink } from 'react-router-dom';

const sampleTasks = [
  { project: 'Project #1', when: '25/05/2021', description: 'Please advise' },
  {
    project: 'Project #2',
    when: '26/05/2021',
    description: 'TOC ready for review',
  },
];

const MemberProfile = () => {
  const { t } = useTranslation();
  return (
    <div>
      <div className="layout-content">
        <div className="p-grid">
          <div className="p-col-4">
            <h2 className="capitalize">{t('MY_FLOWS')}</h2>
            <h4>Leading</h4>
            <ul className="horizontal-unstyled">
              <li>
                <NavLink to="/">Project #1</NavLink>
              </li>
              <li>
                <NavLink to="/">Project #2</NavLink>
              </li>
            </ul>
            <h4>{t('MEMBER')}</h4>
            <ul className="horizontal-unstyled">
              <li>
                <NavLink to="/">Project #3</NavLink>
              </li>
              <li>
                <NavLink to="/">Project #4</NavLink>
              </li>
            </ul>
            <h4>{t('REVIEWER')}</h4>
            <ul className="horizontal-unstyled">
              <li>
                <NavLink to="/">Project #5</NavLink>
              </li>
              <li>
                <NavLink to="/">Project #6</NavLink>
              </li>
            </ul>
          </div>
          <div className="p-col-8">
            <h2>{t('MY_TASKS')}</h2>
            <DataTable value={sampleTasks}>
              <Column field="project" header={t('PROJECT')} />
              <Column field="when" header={t('WHEN')} />
              <Column field="description" header={t('DESCRIPTION')} />
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  );
};

export default MemberProfile;
