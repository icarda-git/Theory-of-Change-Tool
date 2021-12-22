import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import { Button } from 'primereact/button';
import { Dropdown } from 'primereact/dropdown';
import 'primereact/resources/primereact.min.css';
import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { NavLink } from 'react-router-dom';

const countries = [
  { label: 'Greece', value: 'greece' },
  { label: 'United Kingdom', value: 'united_kingdom' },
  { label: 'USA', value: 'usa' },
];

const organizations = [
  { label: 'ICARDA', value: 'icarda' },
  { label: 'IFRPI', value: 'ifrpi' },
  { label: 'IRRI', value: 'irri' },
];

const StakeholderProfile = () => {
  const { t } = useTranslation();
  const [country, setCountry] = useState(null);
  const [org, setOrg] = useState(null);
  const [searchResults, setResults] = useState([]);

  const search = () => {
    setResults(['Project #1', 'Project #3', 'Project #4']);
  };

  return (
    <div>
      <div className="layout-content">
        <div className="p-grid">
          <div className="p-col-4">
            <h2>{t('MY_FLOWS')}</h2>
            <ul className="horizontal-unstyled">
              <li>
                <NavLink to="/">Project #1</NavLink>
              </li>
              <li>
                <NavLink to="/">Project #2</NavLink>
              </li>
            </ul>
            <h2>{t('MY_INITIATIVES')}</h2>
            <ul className="horizontal-unstyled">
              <li>
                <NavLink to="/">Project #3</NavLink>
              </li>
              <li>
                <NavLink to="/">Project #4</NavLink>
              </li>
            </ul>
          </div>
          <div className="p-col-8">
            <h4>{t('FIND_PROJECTS_INITIATIVES')}</h4>
            <Dropdown
              value={country}
              options={countries}
              onChange={(e) => setCountry(e.value)}
              placeholder={t('SEARCH_BY_COUNTRY')}
              className="p-mr-2"
            />
            <Dropdown
              value={org}
              options={organizations}
              onChange={(e) => setOrg(e.value)}
              placeholder={t('SEARCH_BY_ORGANIZATION')}
              className="p-mr-2"
            />
            <Button onClick={search} label={t('SEARCH')} icon="pi pi-search" />
            {searchResults.length > 0 && (
              <div className="p-mt-4">
                {searchResults.map((r) => (
                  <div className="p-mb-2">
                    <strong>{r}</strong>&nbsp;
                    <i className="pi pi-info-circle p-ml-2" />
                    <Button
                      label={t('REQUEST_ACCESS')}
                      icon="pi pi-angle-right"
                      className="p-ml-4 p-button-sm"
                    />
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default StakeholderProfile;
