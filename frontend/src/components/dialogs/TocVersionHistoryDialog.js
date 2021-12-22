import { Button } from 'primereact/button';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import { Dialog } from 'primereact/dialog';
import React, { useContext, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useHistory } from 'react-router-dom';
import { getTocVersions } from '../../services/flows';
import { ToastContext } from '../../store';
import { getTocType } from '../../utilities/helpers';

const TocVersionHistoryDialog = ({ dialogOpen, setDialogOpen, flowId, toc, type }) => {
  const { t } = useTranslation();
  const [versions, setVersions] = useState([]);
  const history = useHistory();
  const { setError } = useContext(ToastContext);
  const [isLoading, setIsLoading] = useState(true);

  const loadTocVersions = async () => {
    try {
      const { data } = await getTocVersions(toc.toc_id);
      setVersions(data);
    } catch (error) {
      setError('Error', 'Failed to load TOC versions.');
    }
    setIsLoading(false);
  };

  useEffect(() => {
    if (dialogOpen) {
      loadTocVersions();
    }
  }, [dialogOpen]); // eslint-disable-line

  return (
    <Dialog
      header={t('TOC_VERSION_HISTORY_TITLE')}
      visible={dialogOpen}
      draggable={false}
      style={{ maxWidth: '600px' }}
      modal
      onHide={() => setDialogOpen(false)}
    >
      <div className="p-grid">
        <div className="p-col-12">
          <DataTable
            emptyMessage={t('NO_TOC_VERSIONS_FOUND')}
            paginator
            rows={5}
            loading={isLoading}
            value={versions}
            className="p-mt-2"
          >
            <Column
              field="version"
              header={t('VERSION')}
              body={({ version }) => `${version}`}
              sortable
            />
            <Column
              field="createdAt"
              header={t('TIMESTAMP')}
              body={({ created_at: createdAt }) => createdAt}
              sortable
            />
            <Column
              header={t('ACTIONS')}
              body={({ _id: id, toc_type: tocType }) => (
                <>
                  <Button
                    onClick={() =>
                      history.push(
                        `/flows/${flowId}/tocs/${id}?flavour=${getTocType(tocType)}&readOnly=true`,
                      )
                    }
                    label="View"
                    icon="pi pi-eye"
                    className="p-button p-button-sm"
                  />
                </>
              )}
            />
          </DataTable>
        </div>
      </div>
    </Dialog>
  );
};

export default TocVersionHistoryDialog;
