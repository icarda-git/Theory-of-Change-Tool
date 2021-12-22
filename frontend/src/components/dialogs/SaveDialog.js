import { Button } from 'primereact/button';
import { Dialog } from 'primereact/dialog';
import React from 'react';
import { useTranslation } from 'react-i18next';

const SaveDialog = ({
  dialogOpen,
  setDialogOpen,
  text,
  submitButtonText,
  cancelButtonText,
  onSubmit,
  onCancel,
}) => {
  const { t } = useTranslation();

  return (
    <Dialog
      header={t('SAVE_CHANGES_DIALOG_TITLE')}
      visible={dialogOpen}
      draggable={false}
      modal
      onHide={() => setDialogOpen(false)}
    >
      <div className="p-fluid">
        <div className="p-formgrid p-grid">
          <form
            onSubmit={(e) => {
              e.preventDefault();
              onSubmit();
              setDialogOpen(false);
            }}
          >
            <div className="p-col-12">
              <p>{text}</p>
            </div>
            <div className="p-col-12 p-text-center p-mt-4">
              <div className="p-d-flex p-fluid p-ai-center p-jc-center">
                <Button
                  type="button"
                  label={cancelButtonText}
                  icon="pi pi-times"
                  className="p-button-secondary p-mr-2 p-mb-2"
                  onClick={() => {
                    onCancel();
                    setDialogOpen(false);
                  }}
                />
                <Button
                  label={submitButtonText}
                  icon="pi pi-save"
                  className="p-mr-2 p-mb-2"
                  type="submit"
                />
              </div>
            </div>
          </form>
        </div>
      </div>
    </Dialog>
  );
};

export default SaveDialog;
