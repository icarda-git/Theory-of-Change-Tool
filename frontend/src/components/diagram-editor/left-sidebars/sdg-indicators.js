import React, { useEffect, useState } from 'react';
import { getSdgCollections } from '../../../services/editor-data';

const SDGIndicators = ({ addNode, usedSDGIndicators }) => {
  const [initialSDGIndicators, setInitialSDGIndicators] = useState([]);
  const [sdgIndicators, setSdgIndicators] = useState([]);

  const loadSdgCollections = async () => {
    const { data } = await getSdgCollections();
    setInitialSDGIndicators(data.sdgs);
    setSdgIndicators(data.sdgs.filter(({ code }) => !usedSDGIndicators.includes(code)));
  };

  useEffect(() => {
    loadSdgCollections();
  }, []); // eslint-disable-line

  useEffect(() => {
    setSdgIndicators(initialSDGIndicators.filter(({ code }) => !usedSDGIndicators.includes(code)));
  }, [usedSDGIndicators]); // eslint-disable-line

  return (
    <div
      className="block"
      style={{
        position: 'absolute',
        zIndex: 99,
        backgroundColor: 'white',
        left: 0,
        height: 'calc(100% - 245px)',
        width: '100px',
        padding: '10px',
        marginTop: '10px',
        overflowY: 'scroll',
        overflowX: 'hidden',
      }}
    >
      {sdgIndicators.length === 0 && (
        <i className="pi pi-spin pi-spinner" style={{ fontSize: '2em' }} />
      )}
      {sdgIndicators.length !== 0 &&
        sdgIndicators.map((sdg) => (
          <div key={sdg.code} className="p-mb-1">
            <span
              role="button"
              tabIndex={0}
              style={{ cursor: 'pointer' }}
              onClick={() => {
                addNode('SDGIndicator', { ...sdg });
              }}
            >
              <img src={sdg.image} alt={sdg.title} title={sdg.title} width="100%" height="auto" />
            </span>
          </div>
        ))}
    </div>
  );
};

export default SDGIndicators;
