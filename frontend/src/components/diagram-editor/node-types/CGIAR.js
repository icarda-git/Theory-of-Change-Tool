import React, { memo } from 'react';
import { Handle } from 'react-flow-renderer';

export default memo(({ data, selected }) => (
  <>
    <div
      style={{
        display: 'inline-block',
        height: '60px',
        backgroundColor: '#377DF2',
        color: '#fff',
        textAlign: 'center',
        width: 'auto',
        padding: '0.5rem',
        border: `3px solid`,
        borderColor: `${selected ? '#000' : 'transparent'}`,
      }}
    >
      {data?.name}
    </div>
    <Handle position="bottom" id="a" />
    <Handle position="top" id="b" />
    <Handle position="right" id="c" />
    <Handle position="left" id="d" />
  </>
));
