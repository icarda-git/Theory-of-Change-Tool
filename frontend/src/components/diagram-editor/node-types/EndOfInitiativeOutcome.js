import React, { memo } from 'react';
import { Handle } from 'react-flow-renderer';

export default memo(
  ({ selected, data }) => (
    <>
      <div
        style={{
          display: 'inline-block',
          height: 'auto',
          backgroundColor: '#103A97',
          color: '#fff',
          fontWeight: 'bold',
          textAlign: 'center',
          border: '3px solid',
          borderColor: `${selected ? 'rgb(130 169 255)' : 'transparent'}`,
          maxWidth: '200px',
          padding: '0.5rem',
          wordBreak: 'break-word',
        }}
      >
        {data && data.description ? data.description : 'End Of Initiative Outcome'}
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
