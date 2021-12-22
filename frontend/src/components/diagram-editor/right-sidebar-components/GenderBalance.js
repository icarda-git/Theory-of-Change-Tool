import { InputTextarea } from 'primereact/inputtextarea';
import React, { useEffect, useState } from 'react';

const GenderDimension = ({ element, activeMetadata, setData, readOnly = false }) => {
  const [genderDimension, setGenderDimension] = useState(
    element ? activeMetadata?.genderDimension || '' : '',
  );

  useEffect(() => {
    if (element) {
      setGenderDimension(activeMetadata?.genderDimension || '');
    }
  }, [element]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setData(element?.id, 'genderDimension', genderDimension);
    }
  }, [genderDimension]); // eslint-disable-line

  return (
    <div className="p-grid">
      <div className="p-col-12">
        <InputTextarea
          disabled={readOnly}
          style={{ width: '100%', resize: 'none' }}
          rows={5}
          onChange={(e) => setGenderDimension(e.target.value)}
          value={genderDimension}
        />
      </div>
    </div>
  );
};

export default GenderDimension;
