import React, { memo } from 'react';
import { Handle } from 'react-flow-renderer';

export default memo(
  ({ data, selected }) => (
    <>
      <div
        style={{
          display: 'inline-block',
          height: 'auto',
          backgroundColor: '#BBB33B',
          color: '#000',
          fontWeight: 'bold',
          borderRadius: '8px',
          border: `3px solid`,
          borderColor: `${selected ? '#000' : 'transparent'}`,
          textAlign: 'center',
          maxWidth: '200px',
          padding: '0.5rem',
          wordBreak: 'break-word',
          fontSize: 'smaller',
        }}
      >
        {data?.description}
      </div>
      <Handle position="bottom" id="a" />
      <Handle position="top" id="b" />
      <Handle position="right" id="c" />
      <Handle position="left" id="d" />
    </>
  ),
  (prevProps, newProps) =>
    prevProps.data.description !== newProps.data.description &&
    prevProps.selected !== newProps.selected,
);
