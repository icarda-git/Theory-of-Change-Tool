import React, { useEffect, useState } from 'react';
import { getImpactAreas } from '../../../services/editor-data';

const CGIARSidebar = ({ addNode }) => {
  const [types, setTypes] = useState([]);

  const loadImpactAreas = async () => {
    const { data } = await getImpactAreas();
    setTypes(data.impact_areas);
  };

  useEffect(() => {
    loadImpactAreas();
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
        width: '180px',
        padding: '10px',
        marginTop: '10px',
        overflowY: 'scroll',
        overflowX: 'hidden',
      }}
    >
      {types.length === 0 && <i className="pi pi-spin pi-spinner" style={{ fontSize: '2em' }} />}
      {types.length !== 0 &&
        types.map((type) => (
          <div key={type.code} className="p-mb-2">
            <span
              style={{
                backgroundColor: '#377DF2',
                color: '#fff',
                textAlign: 'center',
                width: '150px',
                padding: '0.5rem',
                display: 'block',
                cursor: 'pointer',
              }}
              role="button"
              tabIndex={0}
              onClick={() => {
                addNode('cgiar', { ...type });
              }}
            >
              {type.name}
            </span>
          </div>
        ))}
    </div>
  );
};

export default CGIARSidebar;
