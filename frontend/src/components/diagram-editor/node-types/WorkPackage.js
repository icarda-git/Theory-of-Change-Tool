import React, { memo } from 'react';
import { Handle } from 'react-flow-renderer';

export default memo(
  ({ selected, data }) => (
    <>
      <div
        style={{
          display: 'inline-block',
          height: 'auto',
          backgroundColor: '#B529DE',
          color: '#fff',
          fontWeight: 'bold',
          textAlign: 'center',
          border: '3px solid',
          borderColor: `${selected ? 'rgb(116 1 150)' : 'transparent'}`,
          maxWidth: '200px',
          padding: '0.5rem',
          wordBreak: 'break-word',
        }}
      >
        {data?.description || 'Work Package'}
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
