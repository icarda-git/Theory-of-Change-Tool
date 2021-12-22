import { Button } from 'primereact/button';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import { InputText } from 'primereact/inputtext';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

const ResponsibleEntitiesTable = ({ entities, removeEntity, readOnly = false }) => {
  const { t } = useTranslation();
  return (
    <DataTable
      paginator
      rows={10}
      emptyMessage={t('NO_RESPONSIBLE_ENTITIES_FOUND')}
      value={entities}
      className="p-mt-2"
    >
      <Column field="type" header={t('TYPE')} />
      <Column
        body={({ type }) => (
          <div className="p-text-right">
            <Button
              disabled={readOnly}
              icon="pi pi-trash"
              className="p-button-danger"
              onClick={() => removeEntity(type)}
            />
          </div>
        )}
      />
    </DataTable>
  );
};

const ResponsibleEntities = ({ element, activeMetadata, setData, readOnly = false }) => {
  const [entityType, setEntityType] = useState('');
  const [entities, setEntities] = useState(element ? activeMetadata?.entities || [] : []);

  useEffect(() => {
    if (element) {
      setData(element?.id, 'entities', entities);
    }
  }, [entities]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setEntities(activeMetadata?.entities || []);
    }
  }, [element]); // eslint-disable-line

  const addEntity = () => {
    setEntities([...entities, { type: entityType }]);
    setEntityType('');
  };

  const removeEntity = (type) => {
    setEntities(entities.filter((e) => e.type !== type));
  };

  return (
    <>
      <div className="p-grid p-formgrid p-fluid">
        <form
          className="p-col-12"
          onSubmit={(e) => {
            e.preventDefault();
            addEntity();
          }}
        >
          <div className="p-formgroup-inline">
            <div className="p-field">
              <InputText
                disabled={readOnly}
                value={entityType}
                onChange={(e) => setEntityType(e.target.value)}
                placeholder="Entity type"
              />
            </div>
            <div className="p-field">
              <Button
                disabled={entityType?.length === 0 || readOnly}
                label="Add"
                onClick={addEntity}
              />
            </div>
          </div>
        </form>
      </div>
      <ResponsibleEntitiesTable
        readOnly={readOnly}
        entities={entities}
        removeEntity={removeEntity}
      />
    </>
  );
};

export default ResponsibleEntities;
