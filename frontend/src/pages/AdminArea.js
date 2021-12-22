import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import { Button } from 'primereact/button';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import { Dialog } from 'primereact/dialog';
import { InputText } from 'primereact/inputtext';
import { InputTextarea } from 'primereact/inputtextarea';
import 'primereact/resources/primereact.min.css';
import { TabPanel, TabView } from 'primereact/tabview';
import React, { useContext, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import Loading from '../components/Loading';
import { acceptFlow, getPendingFlows, rejectFlow } from '../services/flows';
import { ToastContext } from '../store';

const AdminArea = () => {
  const { t } = useTranslation();
  const { setError, setSuccess } = useContext(ToastContext);
  const [filter, setFilter] = useState('');
  const [rows, setRows] = useState(10);
  const [isLoading, setIsLoading] = useState(true);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [pendingFlows, setPendingFlows] = useState([]);
  const [selectedFlow, setSelectedFlow] = useState(null);
  const [rationale, setRationale] = useState('');

  const tableHeader = (
    <div className="p-d-flex p-flex-row p-jc-between p-ai-center">
      <h4 className="p-my-0 p-text-capitalize">{t('TOC_REQUESTS')}</h4>
      <span className="p-input-icon-left">
        <i className="pi pi-search" />
        <InputText
          value={filter}
          onChange={(e) => setFilter(e.target.value)}
          className="p-mr-3"
          placeholder={t('SEARCH_FOR_REQUEST')}
        />
      </span>
    </div>
  );

  const fieldTemplate = (rowData, field) => <span>{rowData[field]}</span>;

  const actionsTemplate = (rowData) => (
    <div className="p-flex p-jc-between">
      <Button
        icon="pi pi-check"
        onClick={() => acceptPendingFlow(rowData?.id)}
        className="p-mr-3"
        label={t('ACCEPT')}
      />
      <Button
        icon="pi pi-times"
        className="p-button-danger"
        label={t('REJECT')}
        onClick={() => {
          setSelectedFlow(rowData);
          setDialogOpen(true);
        }}
      />
    </div>
  );

  const transformFlows = (flows) =>
    flows.map(({ id, name: project, leader: { name, email } }) => ({
      id,
      project,
      name,
      email,
    }));

  const loadPendingFlows = async () => {
    setIsLoading(true);
    try {
      const { data } = await getPendingFlows();
      setPendingFlows(transformFlows(data));
    } catch (error) {
      setError(error.message);
    }
    setIsLoading(false);
  };

  const acceptPendingFlow = async (id) => {
    try {
      await acceptFlow(id);
      setSuccess('The flow was accepted!');
      await loadPendingFlows();
    } catch (error) {
      setError(error.message);
    }
  };

  const rejectPendingFlow = async (id) => {
    try {
      await rejectFlow(id, { rationale });
      setSuccess('The flow was rejected!');
      setRationale('');
      await loadPendingFlows();
    } catch (error) {
      setError(error.message);
    }
  };

  useEffect(() => {
    loadPendingFlows();
  }, []); // eslint-disable-line

  if (isLoading) {
    return <Loading />;
  }

  return (
    <>
      <div className="requests-page p-pb-5">
        <TabView>
          <TabPanel header="New Requests">
            <DataTable
              header={tableHeader}
              globalFilter={filter}
              paginator
              rows={rows}
              rowsPerPageOptions={[10, 20, 50]}
              emptyMessage={t('NO_REQUESTS_FOUND')}
              value={pendingFlows}
              className="p-mt-2"
            >
              <Column
                field="name"
                header={t('NAME')}
                sortable
                body={(rowData) => fieldTemplate(rowData, 'name')}
              />
              <Column
                field="email"
                sortable
                header={t('EMAIL')}
                body={(rowData) => fieldTemplate(rowData, 'email')}
              />
              <Column
                field="project"
                sortable
                header={t('PROJECT')}
                body={(rowData) => fieldTemplate(rowData, 'project')}
              />
              <Column header={t('ACTIONS')} body={actionsTemplate} />
            </DataTable>
            <Dialog
              header={t('RATIONALE_TITLE')}
              visible={dialogOpen}
              style={{ width: '500px' }}
              modal
              onHide={() => {
                setDialogOpen(false);
                setRationale('');
              }}
            >
              <div className="p-fluid p-formgrid p-grid p-justify-start">
                <div className="p-col-12">
                  <div className="p-field">
                    <label htmlFor="rationale">Rationale</label>
                    <InputTextarea
                      id="rationale"
                      onChange={(e) => setRationale(e.target.value)}
                      value={rationale}
                      autoResize={false}
                      autoFocus
                      rows={5}
                    />
                  </div>
                  <div className="p-field">
                    <Button
                      icon="pi pi-times"
                      className="p-button-danger"
                      label={t('REJECT')}
                      onClick={() => {
                        rejectPendingFlow(selectedFlow?.id);
                        setDialogOpen(false);
                      }}
                    />
                  </div>
                </div>
              </div>
            </Dialog>
          </TabPanel>
          <TabPanel header="History">
            <DataTable
              header={tableHeader}
              globalFilter={filter}
              paginator
              rows={rows}
              rowsPerPageOptions={[10, 20, 50]}
              onPage={(event) => setRows(event.rows)}
              emptyMessage={t('NO_REQUESTS_FOUND')}
              value={pendingFlows}
              className="p-mt-2"
            >
              <Column
                field="name"
                header={t('NAME')}
                sortable
                body={(rowData) => fieldTemplate(rowData, 'name')}
              />
              <Column
                field="email"
                sortable
                header={t('EMAIL')}
                body={(rowData) => fieldTemplate(rowData, 'email')}
              />
              <Column
                field="project"
                sortable
                header={t('PROJECT')}
                body={(rowData) => fieldTemplate(rowData, 'project')}
              />
              <Column
                field="status"
                sortable
                header={t('STATUS')}
                body={(rowData) => fieldTemplate(rowData, 'status')}
              />
            </DataTable>
          </TabPanel>
        </TabView>
      </div>
    </>
  );
};

export default AdminArea;
