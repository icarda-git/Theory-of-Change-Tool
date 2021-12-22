import React, { memo } from 'react';
import { Handle } from 'react-flow-renderer';
import genderDimension from '../../../assets/img/gender-balance.png';

export default memo(
  ({ selected, data }) => (
    <>
      <div
        style={{
          display: 'inline-block',
          height: 'auto',
          backgroundColor: '#929292',
          color: '#fff',
          fontWeight: 'bold',
          textAlign: 'center',
          border: '3px solid',
          borderColor: `${selected ? 'rgb(101 100 100)' : 'transparent'}`,
          minWidth: '150px',
          maxWidth: '200px',
          padding: '0.5rem',
          wordBreak: 'break-word',
          position: 'relative',
        }}
      >
        {data && data.description ? data.description : 'Outcome'}
        {data && data.hasgenderDimension && (
          <div style={{ position: 'absolute', bottom: '-15px', right: '5px', width: '25px' }}>
            <img src={genderDimension} alt="Gender Balance" width="80%" />
          </div>
        )}
      </div>
      <Handle position="bottom" id="a" />
      <Handle position="top" id="b" />
      <Handle position="right" id="c" />
      <Handle position="left" id="d" />
    </>
  ),
  (prevProps, newProps) =>
    prevProps.data.description !== newProps.data.description &&
    prevProps.data.hasgenderDimension !== newProps.data.hasgenderDimension &&
    prevProps.selected !== newProps.selected,
);
