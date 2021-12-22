import { Button } from 'primereact/button';
import { Dialog } from 'primereact/dialog';
import { InputTextarea } from 'primereact/inputtextarea';
import React, { useEffect, useState } from 'react';
import { useUpdateNodeInternals } from 'react-flow-renderer';
import { useTranslation } from 'react-i18next';

const ChangeNodeTextModal = ({
  open,
  setOpen,
  elements,
  setElements,
  selectedElement,
}) => {
  const updateNodeInternals = useUpdateNodeInternals();
  const { t } = useTranslation();
  const [text, setText] = useState('');

  const onSubmit = async (e) => {
    e.preventDefault();
    const oldElement = selectedElement;
    oldElement.data.description = text;
    setElements(
      elements.filter((el) => el.id !== selectedElement.id).concat(oldElement),
    );
    updateNodeInternals(oldElement.id);
    setOpen(false);
  };

  useEffect(() => {
    setText(
      selectedElement &&
        selectedElement.data &&
        selectedElement.data.description,
    );
  }, [selectedElement]);

  return (
    <Dialog
      header={t('CHANGE_ELEMENT_TEXT')}
      visible={open}
      style={{ width: '500px', zIndex: 999999999999 }}
      draggable={false}
      modal
      onHide={() => setOpen(false)}
    >
      <form onSubmit={onSubmit}>
        <div className="p-fluid">
          <div className="p-formgrid p-grid">
            <div className="p-col-12">
              <div className="p-field">
                <label htmlFor="members">{t('TEXT')}</label>
                <InputTextarea
                  rows={5}
                  value={text}
                  onChange={(e) => setText(e.target.value)}
                  style={{ resize: 'none' }}
                  className="w-full"
                />
              </div>
            </div>
            <div className="p-col-12 p-text-center p-mt-3">
              <div className="p-d-inline-flex p-col-6 p-ai-center p-jc-center">
                <Button
                  label={t('SET_TEXT')}
                  icon="pi pi-save"
                  type="submit"
                  className="p-mr-2 p-mb-2"
                />
              </div>
            </div>
          </div>
        </div>
      </form>
    </Dialog>
  );
};

export default ChangeNodeTextModal;
