import { InputTextarea } from 'primereact/inputtextarea';
import React, { useEffect, useState } from 'react';

const Narrative = ({
  element,
  activeMetadata,
  setData,
  narrative,
  setNarrative,
  readOnly = false,
}) => {
  const [n, setN] = useState(element ? activeMetadata?.narrative : narrative);

  useEffect(() => {
    if (element) {
      setN(activeMetadata?.narrative || '');
    } else {
      setN(narrative);
    }
  }, [activeMetadata, narrative]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setData(element.id, 'narrative', n);
    } else {
      setNarrative(n);
    }
  }, [n]); // eslint-disable-line

  return (
    <>
      <div className="p-grid">
        <div className="p-col-12">
          <InputTextarea
            disabled={readOnly}
            style={{ width: '100%', resize: 'none' }}
            rows={3}
            onChange={(e) => setN(e.target.value)}
            value={n || ''}
            autoResize
          />
        </div>
      </div>
    </>
  );
};

export default Narrative;
