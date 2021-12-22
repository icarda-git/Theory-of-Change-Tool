import React, { memo } from 'react';
import { Handle } from 'react-flow-renderer';

export default memo(({ data, selected }) => (
  <>
    <div
      style={{
        display: 'inline-block',
        width: '120px',
        height: '120px',
        position: 'relative',
        border: `3px solid`,
        borderColor: `${selected ? '#000' : 'transparent'}`,
      }}
    >
      <img
        src={`${data?.image}?v=1`}
        style={{ position: 'absolute', width: '100%', height: '100%' }}
        alt="img"
      />
      <div
        style={{
          position: 'absolute',
          zIndex: '99',
          width: '100%',
          height: '100%',
        }}
      />
    </div>
    <Handle position="bottom" id="a" style={{ bottom: 1 }} />
    <Handle position="top" id="b" />
    <Handle position="right" id="c" />
    <Handle position="left" id="d" />
  </>
));
