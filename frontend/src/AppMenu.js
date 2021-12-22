import { nanoid } from 'nanoid';
import React, { useContext, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { NavLink } from 'react-router-dom';
import LogoImage from './assets/img/logo-toc.png';
import { getTocs } from './services/flows';
import { UserContext } from './store';
import { getTocType } from './utilities/helpers';

const AppMenu = () => {
  const { t } = useTranslation();
  const adminRoles = ['superadministrator', 'administrator'];
  const { flows, user, currentFlowId, setUser } = useContext(UserContext);
  const [tocs, setTocs] = useState([]);

  useEffect(() => {
    if (currentFlowId) {
      getTocs(currentFlowId).then((data) => setTocs(data));
    }
  }, [currentFlowId, flows]);

  return (
    <div className="layout-sidebar" role="button" tabIndex="0" style={{ zIndex: 999 }}>
      <div className="logo p-p-1">
        <NavLink to="/">
          <img src={LogoImage} alt="Logo" style={{ maxWidth: '100%', height: '80px' }} />
        </NavLink>
      </div>

      <div className="layout-menu-container">
        <ul className="layout-menu" role="menu">
          <li className="layout-root-menuitem" role="menuitem">
            <div className="layout-menuitem-root-text p-pt-0 p-pb-2">{t('OVERVIEW')}</div>
          </li>
          <ul className="layout-menu" role="menu">
            <li role="menuitem">
              <div className="layout-root-menuitem">
                <NavLink
                  to="/introduction"
                  className="p-d-flex p-jc-between p-ai-center"
                  activeClassName="p-button p-component layout-root-menuitem"
                  exact
                  onClick={() => setUser({ currentFlowId: null })}
                >
                  <span className="layout-menuitem-text">
                    <i className="layout-menuitem-icon pi pi-fw pi-book" />
                    {t('INTRO')}
                  </span>
                </NavLink>
              </div>
            </li>
            <li className="p-mt-2" role="menuitem">
              <div className="layout-root-menuitem">
                <NavLink
                  to="/"
                  className="p-d-flex p-jc-between p-ai-center"
                  activeClassName="p-button p-component layout-root-menuitem"
                  exact
                  onClick={() => setUser({ currentFlowId: null })}
                >
                  <span className="layout-menuitem-text">
                    <i className="layout-menuitem-icon pi pi-fw pi-th-large" />
                    {t('DASHBOARD')}
                  </span>
                </NavLink>
              </div>
            </li>
            <li className="menu-separator p-mb-1" role="separator" />
            <li className="layout-root-menuitem p-d-flex p-jc-between p-ai-center" role="menuitem">
              <div className="layout-menuitem-root-text p-py-1">{t('MY_FLOWS')}</div>
            </li>
            {flows.length === 0 && (
              <li className="p-pt-1">
                <div style={{ color: 'rgba(255, 255, 255, 0.8)' }}>No flows were found.</div>
              </li>
            )}
            {flows.length > 0 &&
              flows.map(({ id: flowId, title }) => (
                <li key={flowId || nanoid()} className="p-mt-1" role="menuitem">
                  <div className="layout-root-menuitem">
                    <NavLink
                      to={`/flows/${flowId}/overview`}
                      className="p-d-flex p-ai-center"
                      exact
                      onClick={() => setUser({ currentFlowId: flowId })}
                    >
                      <span className="layout-menuitem-text">
                        <i className="layout-menuitem-icon pi pi-fw pi-folder-open" />
                        {title}
                      </span>
                    </NavLink>
                  </div>
                </li>
              ))}
            <li className="menu-separator" role="separator" />
            {flows.length > 0 &&
              flows.map(
                ({ id: flowId, title }) =>
                  currentFlowId === flowId && (
                    <li key={flowId || nanoid()} className="layout-root-menuitem" role="menuitem">
                      <div className="layout-menuitem-root-text p-py-0">{title}</div>
                    </li>
                  ),
              )}
            {flows.length > 0 &&
              flows.map(
                ({ title, id: flowId, pdb_link: participatoryLink }) =>
                  currentFlowId === flowId && (
                    <ul key={flowId || nanoid()} className="layout-menu p-mt-2" role="menu">
                      <li className="layout-root-menuitem" role="menuitem">
                        <NavLink
                          to={`/flows/${flowId}/overview`}
                          className="p-d-flex p-pl-3"
                          activeClassName="p-button p-component layout-root-menuitem"
                          exact
                        >
                          <span className="layout-menuitem-text">Overview</span>
                        </NavLink>
                      </li>
                      {participatoryLink && (
                        <li className="layout-root-menuitem p-mt-1" role="menuitem">
                          <a
                            target="_blank"
                            rel="noreferrer"
                            href={participatoryLink}
                            className="p-d-flex p-pl-3"
                          >
                            <span className="layout-menuitem-text">Participatory Development</span>
                          </a>
                        </li>
                      )}
                      {tocs.length > 0 &&
                        tocs
                          .sort((a, b) => Number(a.number) - Number(b.number))
                          .map(
                            ({ number, toc_id: tocId, toc_title: tocTitle, toc_type: tocType }) => (
                              <li
                                key={tocId || nanoid()}
                                className="layout-root-menuitem p-mt-1"
                                role="menuitem"
                              >
                                <NavLink
                                  to={`/flows/${flowId}/tocs/${tocId}?flavour=${getTocType(
                                    tocType,
                                  )}`}
                                  className="p-d-flex p-pl-3"
                                  activeClassName="p-button p-component layout-root-menuitem"
                                  exact
                                >
                                  <span className="layout-menuitem-text">
                                    {getTocType(tocType) === 'work-package-level'
                                      ? `WP ${number}: ${tocTitle}`
                                      : `${title}`}
                                  </span>
                                </NavLink>
                              </li>
                            ),
                          )}
                    </ul>
                  ),
              )}
            {flows.length > 0 &&
              flows.map(
                ({ id: flowId }) =>
                  currentFlowId === flowId && (
                    <li key={flowId || nanoid()} className="menu-separator" role="separator" />
                  ),
              )}
            {adminRoles.includes(user?.role?.toLowerCase()) && (
              <>
                <li className="layout-root-menuitem" role="menuitem">
                  <div className="layout-menuitem-root-text p-py-0">{t('ADMIN_AREA')}</div>
                </li>
                <li className="p-mt-2" role="menuitem">
                  <div className="layout-root-menuitem">
                    <NavLink
                      to="/admin-area"
                      className="p-d-flex p-jc-between p-ai-center"
                      activeClassName="p-button p-component layout-root-menuitem"
                      exact
                      onClick={() => setUser({ currentFlowId: null })}
                    >
                      <span className="layout-menuitem-text">
                        <i className="layout-menuitem-icon pi pi-fw pi-users" />
                        {t('TOC_REQUESTS')}
                      </span>
                    </NavLink>
                  </div>
                </li>
              </>
            )}
          </ul>
        </ul>
      </div>
    </div>
  );
};

export default AppMenu;
