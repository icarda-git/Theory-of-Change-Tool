import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import { Button } from 'primereact/button';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import { InputText } from 'primereact/inputtext';
import 'primereact/resources/primereact.min.css';
import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';

const invitations = [
  {
    text: 'John Doe has invited you to join Test Project 1 as co-leader.',
  },
  {
    text: 'John Doe has invited you to join Test Project 2 as co-leader.',
  },
  {
    text: 'John Doe has invited you to join Test Project 3 as co-leader.',
  },
  {
    text: 'John Doe has invited you to join Test Project 4 as co-leader.',
  },
  {
    text: 'John Doe has invited you to join Test Project 5 as co-leader.',
  },
  {
    text: 'John Doe has invited you to join Test Project 6 as co-leader.',
  },
  {
    text: 'John Doe has invited you to join Test Project 7 as co-leader.',
  },
  {
    text: 'John Doe has invited you to join Test Project 8 as co-leader.',
  },
];

const Invitations = () => {
  const { t } = useTranslation();
  const [filter, setFilter] = useState('');
  const [rows, setRows] = useState(10);

  const tableHeader = (
    <div className="p-d-flex p-flex-row p-jc-between p-ai-center">
      <h4 className="p-my-0 p-text-uppercase">{t('INVITATIONS')}</h4>
      <span className="p-input-icon-left">
        <i className="pi pi-search" />
        <InputText
          value={filter}
          onChange={(e) => setFilter(e.target.value)}
          className="p-mr-3"
          placeholder={t('SEARCH_FOR_INVITATIONS')}
        />
      </span>
    </div>
  );

  const inviteTextTemplate = (rowData) => <span>{rowData.text}</span>;

  const actionsTemplate = (rowData) => (
    <span>
      <Button icon="pi pi-check" className="p-mr-3" label={t('JOIN')} />
      <Button icon="pi pi-times" className="p-button-danger" label={t('DISMISS')} />
    </span>
  );

  return (
    <>
      <div className="invitations-page">
        <DataTable
          header={tableHeader}
          globalFilter={filter}
          paginator
          rows={rows}
          rowsPerPageOptions={[10, 20, 50]}
          onPage={(event) => setRows(event.rows)}
          emptyMessage={t('NO_INVITATIONS_FOUND')}
          value={invitations}
          className="p-mt-2"
        >
          <Column field="text" header={t('INVITATION')} sortable body={inviteTextTemplate} />
          <Column field="actions" header={t('ACTIONS')} body={actionsTemplate} />
        </DataTable>
      </div>
    </>
  );
};

export default Invitations;
