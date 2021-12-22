import { InputTextarea } from 'primereact/inputtextarea';
import React, { useEffect, useState } from 'react';

const Challenge = ({
  element,
  activeMetadata,
  setData,
  challenge,
  setChallenge,
  readOnly = false,
}) => {
  const [n, setN] = useState((element && (activeMetadata?.challenge || '')) || challenge || '');

  useEffect(() => {
    if (element) {
      setN(activeMetadata?.challenge);
    } else {
      setN(challenge);
    }
  }, [element, challenge]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setData('challenge', n);
    } else {
      setChallenge(n);
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
            value={n}
            autoResize
          />
        </div>
      </div>
    </>
  );
};

export default Challenge;
