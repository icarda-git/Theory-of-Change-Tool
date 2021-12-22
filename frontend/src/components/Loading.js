import React from 'react';

const Loading = () => (
  <div className="p-d-flex p-jc-center p-ai-center" style={{ width: '100%', height: '100vh' }}>
    <div className="p-text-center">
      <h4 className="p-mb-4">Loading</h4>
      <i className="pi pi-spin pi-spinner" style={{ fontSize: '2.5em' }} />
    </div>
  </div>
);

export default Loading;
