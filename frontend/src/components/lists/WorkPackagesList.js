import { nanoid } from 'nanoid';
import { Button } from 'primereact/button';
import { InputText } from 'primereact/inputtext';
import React from 'react';

const WorkPackagesList = ({ items, setItems }) => {
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
        {items.map(({ id, number, title }) => (
          <div key={id || nanoid()} className="p-grid p-fluid p-mb-0">
            <div className="p-col-2">
              <InputText type="text" value={number || 'N/A'} readOnly disabled />
            </div>
            <div className="p-col-9">
              <InputText
                type="text"
                placeholder="Work Package Title"
                value={title || ''}
                onChange={(e) => updateItem(id, { title: e.target.value })}
              />
            </div>
            <div className="p-col-1">
              <Button
                onClick={() => removeItem(id)}
                icon="pi pi-trash"
                className="p-button-danger p-button-sm"
              />
            </div>
          </div>
        ))}
      </div>
    </>
  );
};

export default WorkPackagesList;
