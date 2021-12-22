import React from 'react';
import ActionAreaOutcomesSidebar from './left-sidebars/action-area-outcomes';
import CGIARSidebar from './left-sidebars/cgiar';
import SDGIndicators from './left-sidebars/sdg-indicators';

const getComponentForType = (type, props) => {
  switch (type) {
    case 'sdg-indicators':
      // eslint-disable-next-line
      return <SDGIndicators {...props} />;
    case 'cgiar':
      // eslint-disable-next-line
      return <CGIARSidebar {...props} />;
    case 'action-area-outcomes':
      // eslint-disable-next-line
      return <ActionAreaOutcomesSidebar {...props} />;
    default:
      return null;
  }
};

const LeftSidebar = ({ type, addNode, actionAreas, usedSDGIndicators }) => (
  <>{getComponentForType(type, { addNode, actionAreas, usedSDGIndicators })}</>
);

export default LeftSidebar;
