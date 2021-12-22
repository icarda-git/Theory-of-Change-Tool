import { Button } from 'primereact/button';
import { InputText } from 'primereact/inputtext';
import React from 'react';

const PendingRequestsList = ({ items, setItems }) => {
  if (!items) {
    return <></>;
  }

  const removeItem = (id) => {
    setItems(items.filter((item) => item.id !== id));
  };

  const updateItem = (id, props) => {
    setItems(items.map((item) => (item.id === id ? { ...item, ...props } : item)));
  };

  return (
    <>
      <div className="p-mb-2">
        {items.map((item) => (
          <div key={item.id} className="p-grid p-formgrid p-d-flex p-ai-center p-mb-2">
            <div className="p-col-9">
              <InputText
                type="text"
                value={item?.name || ''}
                onChange={(e) => updateItem(item.id, { name: e.target.value })}
              />
              <span className="p-ml-3">Member</span>
            </div>
            <div className="p-col-3">
              <Button
                onClick={() => removeItem(item.id)}
                label="Accept"
                icon="pi pi-check"
                className="p-button-icon-only p-button-sm p-mr-2"
              />
              <Button
                onClick={() => removeItem(item.id)}
                label="Reject"
                icon="pi pi-times"
                className="p-button-danger p-button-icon-only p-button-sm"
              />
            </div>
          </div>
        ))}
      </div>
    </>
  );
};

export default PendingRequestsList;
