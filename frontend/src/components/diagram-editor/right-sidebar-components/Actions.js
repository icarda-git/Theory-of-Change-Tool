import { Button } from 'primereact/button';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import { InputText } from 'primereact/inputtext';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

const ActionsTable = ({ actions, removeAction, readOnly = false }) => {
  const { t } = useTranslation();
  return (
    <DataTable
      paginator
      rows={10}
      emptyMessage={t('NO_ACTIONS_FOUND')}
      value={actions}
      className="p-mt-2"
    >
      <Column field="description" header={t('ACTION')} />
      <Column
        body={(rowData) => (
          <div className="p-text-right">
            <Button
              icon="pi pi-trash"
              disabled={readOnly}
              className="p-button-danger"
              onClick={() => removeAction(rowData.description)}
            />
          </div>
        )}
      />
    </DataTable>
  );
};

const Actions = ({ element, activeMetadata, setData, readOnly = false }) => {
  const [description, setDescription] = useState('');
  const [actor, setActor] = useState('');
  const [scopes, setScopes] = useState([]);
  const [assumptions, setAssumptions] = useState([]);
  const [assumptionsCount, setAssumptionsCount] = useState(0);
  const [scopesCount, setScopesCount] = useState(0);
  const [actions, setActions] = useState(activeMetadata?.actions || []);

  useEffect(() => {
    if (element) {
      setData(element.id, 'actions', actions);
    }
  }, [actions]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setActions(activeMetadata?.actions || []);
    }
  }, [element]); // eslint-disable-line

  const addAction = () => {
    setActions([...actions, { description, actor, scopes, assumptions }]);
    setDescription('');
    setActor('');
    setScopes([]);
    setAssumptions([]);
  };

  const removeAction = (d) => {
    setActions(actions.filter((a) => a.description !== d));
  };

  const addScope = () => {
    setScopesCount(scopesCount + 1);
    setScopes(scopes.concat({ key: scopesCount, value: '' }));
  };

  const removeScope = async (key) => {
    setScopes(scopes.filter((s) => s.key !== key));
  };

  const changeScopeValue = (key, value) => {
    setScopes(
      scopes.map((s) => {
        if (s.key === key) {
          return { key, value };
        }
        return s;
      }),
    );
  };

  const addAssumption = () => {
    setAssumptionsCount(assumptionsCount + 1);
    setAssumptions(assumptions.concat({ key: assumptionsCount, value: '' }));
  };

  const removeAssumption = (key) => {
    setAssumptions(assumptions.filter((a) => a.key !== key));
  };

  const changeAssumptionValue = (key, value) => {
    setAssumptions(
      assumptions.map((a) => {
        if (a.key === key) {
          return { key, value };
        }
        return a;
      }),
    );
  };

  return (
    <>
      {!readOnly && (
        <div>
          <div className="p-grid p-formgrid p-fluid">
            <div className="p-col-12">
              <div className="p-field">
                <InputText
                  value={description}
                  onChange={(e) => setDescription(e.target.value)}
                  placeholder="Action description"
                  disabled={readOnly}
                />
              </div>
            </div>
            <div className="p-col-12">
              <div className="p-field">
                <InputText
                  value={actor}
                  disabled={readOnly}
                  onChange={(e) => setActor(e.target.value)}
                  placeholder="Responsible actor"
                />
              </div>
            </div>
          </div>
          <div className="p-grid p-formgrid">
            <div className="p-col-12">
              <div className="p-field p-mb-0">
                <label htmlFor="scope">
                  Geographical Scope
                  <button
                    type="button"
                    disabled={readOnly}
                    style={{ border: 'none' }}
                    onClick={() => addScope()}
                    className="p-ml-2 badge rounded-full text-white"
                  >
                    +
                  </button>
                </label>
              </div>
              {scopes.length > 0 &&
                scopes.map(({ key, value }) => (
                  <div key={key} className="p-fluid">
                    <div className="p-d-flex p-jc-between p-ai-start">
                      <InputText
                        disabled={readOnly}
                        className="p-mb-3 p-mr-2"
                        value={value}
                        onChange={(e) => changeScopeValue(key, e.target.value)}
                      />
                      <Button
                        disabled={readOnly}
                        icon="pi pi-trash"
                        className="p-button-danger"
                        onClick={() => removeScope(key)}
                      />
                    </div>
                  </div>
                ))}
            </div>
            <div className="p-col-12">
              <div className="p-field p-mb-0">
                <label htmlFor="assumptions">
                  Assumptions
                  <button
                    disabled={readOnly}
                    type="button"
                    style={{ border: 'none' }}
                    onClick={() => addAssumption()}
                    className="p-ml-2 badge rounded-full text-white"
                  >
                    +
                  </button>
                </label>
              </div>
              {assumptions.length > 0 &&
                assumptions.map(({ key, value }) => (
                  <div key={key} className="p-fluid">
                    <div className="p-d-flex p-jc-between p-ai-start">
                      <InputText
                        disabled={readOnly}
                        className="p-mb-3 p-mr-2"
                        value={value}
                        onChange={(e) => changeAssumptionValue(key, e.target.value)}
                      />
                      <Button
                        disabled={readOnly}
                        icon="pi pi-trash"
                        className="p-button-danger"
                        onClick={() => removeAssumption(key)}
                      />
                    </div>
                  </div>
                ))}
            </div>
          </div>
          <div className="p-grid p-formgrid">
            <div className="p-col-12 p-text-right">
              <Button
                disabled={(assumptions.length === 0 && scopes.length === 0) || readOnly}
                label="Add"
                onClick={addAction}
              />
            </div>
          </div>
        </div>
      )}

      <ActionsTable readOnly={readOnly} actions={actions} removeAction={removeAction} />
    </>
  );
};

export default Actions;
