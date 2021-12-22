import { Button } from 'primereact/button';
import React from 'react';
import { useTranslation } from 'react-i18next';
import AppBreadcrumb from './AppBreadcrumb';

const AppTopbar = ({ onMenuButtonClick, routers, profile, signOut }) => {
  const { t } = useTranslation();

  return (
    <div className="layout-topbar">
      <div className="topbar-left">
        <button type="button" className="menu-button p-link" onClick={onMenuButtonClick}>
          <i className="pi pi-chevron-left" />
        </button>
        <span className="topbar-separator" />

        <div className="layout-breadcrumb viewname p-text-capitalize">
          <AppBreadcrumb routers={routers} />
        </div>
      </div>

      <div className="topbar-right">
        <ul className="topbar-menu">
          <li className="p-d-flex p-ai-center">
            <img src="/assets/img/user-default.png" alt="user" className="profile-image p-mr-4" />
            <span className="p-mr-4 profile-name">
              <small>{t('LOGGED_IN_AS')}</small>
              <br />
              {`${profile.first_name} ${profile.last_name}`}
            </span>
          </li>
          <li>
            <Button
              onClick={signOut}
              title={t('SIGN_OUT')}
              label={t('SIGN_OUT')}
              icon="pi pi-sign-out"
              className="p-button-info p-button-sm"
            />
          </li>
        </ul>
      </div>
    </div>
  );
};

export default AppTopbar;
