import { Button } from 'primereact/button';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useHistory, useParams } from 'react-router-dom';
import TocVersionHistoryDialog from '../dialogs/TocVersionHistoryDialog';

const Tocs = ({ tocs }) => {
  const { t } = useTranslation();
  const history = useHistory();
  const { flowId } = useParams();
  const [selectedToc, setSelectedToc] = useState(null);
  const [versionHistoryDialogOpen, setVersionHistoryDialogOpen] = useState(false);

  return (
    <>
      <div className="p-my-3">
        <div className="p-grid">
          <div className="p-col-12">
            <label htmlFor="title">{t('TOCS_LIST_TITLE')}</label>
          </div>
        </div>
        <div className="p-grid p-formgrid">
          <div className="p-col-12 p-md-12 p-lg-12">
            <DataTable
              paginator
              rows={10}
              emptyMessage={t('NO_TOCS_FOUND')}
              value={tocs}
              className="p-mt-2"
            >
              <Column
                field="toc_title"
                header={t('TITLE')}
                sortable
                body={({ toc_title: title, number, type }) => (
                  <span>
                    {type === 'work-package-level' && `WP ${number}:`} {title}
                  </span>
                )}
              />
              <Column field="type" header={t('TYPE')} sortable body={({ type }) => type} />
              <Column
                header={t('ACTIONS')}
                body={(toc) => (
                  <>
                    <Button
                      onClick={() =>
                        history.push(`/flows/${flowId}/tocs/${toc.toc_id}?flavour=${toc.type}`)
                      }
                      label="Edit"
                      icon="pi pi-pencil"
                      className="p-button-success p-button-sm p-mr-2 p-mb-2"
                    />
                    {toc?.published && (
                      <>
                        <a href={toc?.published_data?.narrativeUrl} rel="noreferrer">
                          <Button
                            onClick={() => {}}
                            label="Narrative"
                            icon="pi pi-comment"
                            className="p-button p-button-sm p-mr-2 p-mb-2"
                          />
                        </a>
                        <a href={toc?.published_data?.imageUrl} rel="noreferrer">
                          <Button
                            onClick={() => {}}
                            label="ToC Diagram"
                            icon="pi pi-image"
                            className="p-button p-button-sm p-mr-2 p-mb-2"
                          />
                        </a>
                      </>
                    )}

                    <Button
                      onClick={() => {
                        setSelectedToc(toc);
                        setVersionHistoryDialogOpen(true);
                      }}
                      label="History"
                      icon="pi pi-file"
                      className="p-button-secondary p-button-sm p-mr-2 p-mb-2"
                    />
                  </>
                )}
              />
            </DataTable>
          </div>
        </div>
        <TocVersionHistoryDialog
          flowId={flowId}
          toc={selectedToc}
          dialogOpen={versionHistoryDialogOpen}
          setDialogOpen={setVersionHistoryDialogOpen}
        />
      </div>
    </>
  );
};

export default Tocs;
