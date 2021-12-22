import React, { useState, useEffect } from 'react';
import { getActionAreaOutcomes } from '../../../services/editor-data';

const ActionAreaOutcomesSidebar = ({ addNode, actionAreas }) => {
  const [types, setTypes] = useState([]);

  const loadActionAreas = async () => {
    const { data } = await getActionAreaOutcomes();
    const filteredTypes = data.action_areas
      .filter((aa) => actionAreas.includes(parseInt(aa.code, 10)))
      .map((aa) => aa.outcomes)
      .flat();
    setTypes(filteredTypes);
  };

  useEffect(() => {
    loadActionAreas();
  }, []); // eslint-disable-line

  return (
    <div
      className="block"
      style={{
        position: 'absolute',
        zIndex: 99,
        backgroundColor: 'white',
        left: 0,
        height: 'calc(100% - 245px)',
        width: '200px',
        padding: '10px',
        marginTop: '10px',
        overflowY: 'scroll',
        overflowX: 'hidden',
      }}
    >
      {types.map(({ code, description }) => (
        <div key={code} className="p-mb-2">
          <span
            style={{
              backgroundColor: '#BBB33B',
              color: '#000',
              textAlign: 'center',
              width: '150px',
              padding: '0.5rem',
              display: 'block',
              cursor: 'pointer',
              fontSize: 'smaller',
            }}
            role="button"
            tabIndex={0}
            onClick={() => {
              addNode('actionAreaOutcome', { description });
            }}
          >
            {description}
          </span>
        </div>
      ))}
    </div>
  );
};

export default ActionAreaOutcomesSidebar;
