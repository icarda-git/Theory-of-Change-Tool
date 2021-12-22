import { Checkbox } from 'primereact/checkbox';
import React, { useEffect, useState } from 'react';

const Targets = ({ element, activeMetadata, setData, availableTargets, readOnly }) => {
  const [selectedTargets, setSelectedTargets] = useState(
    element ? activeMetadata?.targets || [] : [],
  );

  const toggleTarget = (code) => {
    if (selectedTargets.includes(code)) {
      setSelectedTargets(selectedTargets.filter((t) => code !== t));
    } else {
      setSelectedTargets([...selectedTargets, code]);
    }
  };

  useEffect(() => {
    if (element) {
      setData(element.id, 'targets', selectedTargets);
    }
  }, [selectedTargets]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setSelectedTargets(activeMetadata?.targets || []);
    }
  }, [element]); // eslint-disable-line

  return (
    <>
      <div className="p-grid p-formgrid">
        {availableTargets.map(({ code, title, checked }) => (
          <div className="p-col-12 p-mb-2" key={code}>
            <Checkbox
              onChange={() => toggleTarget(code)}
              checked={selectedTargets.includes(code)}
              id={`target-${code}`}
              disabled={readOnly}
            />
            <label htmlFor={`target-${code}`} className="p-ml-2 p-checkbox-label">
              {code} {title}
            </label>
          </div>
        ))}
      </div>
    </>
  );
};

export default Targets;
