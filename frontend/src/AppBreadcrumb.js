import { nanoid } from 'nanoid';
import React from 'react';
import { matchPath, NavLink, useLocation, withRouter } from 'react-router-dom';

const AppBreadcrumb = ({ routers }) => {
  const { pathname } = useLocation();

  let breadcrumbs = [];
  let params = {};
  if (routers) {
    // eslint-disable-next-line
    const currentRouter = routers.find((router) => {
      const mp = matchPath(pathname, { path: router.path });
      return mp ? mp.isExact : false;
    });
    params = matchPath(pathname, { exact: false, path: currentRouter.path })?.params;
    breadcrumbs = currentRouter.meta.breadcrumb;
  }

  return breadcrumbs.map(({ label, link }, index) => (
    <span key={nanoid()}>
      <span>{link && <NavLink to={link(params)}>{label}</NavLink>}</span>
      {index !== breadcrumbs.length - 1 && <span className="p-mx-2">&gt;</span>}
    </span>
  ));
};

export default withRouter(AppBreadcrumb);
