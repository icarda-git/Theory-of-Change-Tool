import React from 'react';
import classNames from 'classnames';

const InformationPanel = ({ sizeClasses, name, value, icon, extraClass }) => (
  <div className={sizeClasses}>
    <div
      className={classNames(
        'card',
        'no-gutter',
        'widget-overview-box',
        extraClass,
      )}
    >
      <div className="p-d-flex p-jc-between">
        <div>
          <span className="overview-icon">
            <i className={icon} />
          </span>
          <span className="overview-title">{name}</span>
        </div>
        <div className="overview-number">{value}</div>
      </div>
    </div>
  </div>
);

export default InformationPanel;
