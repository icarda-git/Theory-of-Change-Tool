import { Button } from 'primereact/button';
import { Checkbox } from 'primereact/checkbox';
import { Column } from 'primereact/column';
import { DataTable } from 'primereact/datatable';
import { InputText } from 'primereact/inputtext';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

const IndicatorsCheckboxes = ({
  availableIndicators,
  selectedIndicators,
  setSelectedIndicators,
  readOnly,
}) => {
  const { t } = useTranslation();

  const toggleIndicator = (code) => {
    if (selectedIndicators?.includes(code)) {
      setSelectedIndicators(selectedIndicators?.filter((c) => code !== c));
    } else {
      setSelectedIndicators([...selectedIndicators, code]);
    }
  };

  return (
    <>
      <div className="p-grid p-formgrid">
        {availableIndicators?.length === 0 && (
          <p className="p-col-12">{t('NO_INDICATORS_FOUND')}</p>
        )}
        {availableIndicators.map(({ code, title }) => (
          <div className="p-col-12 p-mb-2" key={code}>
            <Checkbox
              onChange={(e) => toggleIndicator(code)}
              disabled={readOnly}
              checked={selectedIndicators?.includes(code) || false}
              id={`target-${code}`}
            />
            <label htmlFor={`target-${code}`} className="p-ml-2 p-checkbox-label">
              {code} {title}
            </label>
          </div>
        ))}
      </div>
    </>
  );
};

const IndicatorsTable = ({
  indicators,
  removeIndicator,
  previewOnly = false,
  readOnly = false,
}) => {
  const { t } = useTranslation();
  return (
    <DataTable paginator rows={10} emptyMessage={t('NO_INDICATORS_FOUND')} value={indicators}>
      <Column
        header={t('INDICATOR')}
        body={(rowData) => {
          if (rowData.title === undefined) return rowData.description;
          return (
            <div>
              {`${rowData.title} (${rowData.target_year})`}
              <br />
              <strong>{`${rowData.value} ${rowData.unit}`}</strong>
            </div>
          );
        }}
      />
      <Column
        body={(rowData) => (
          <div className="p-text-right">
            <Button
              disabled={readOnly}
              icon="pi pi-trash"
              className="p-button-danger"
              onClick={() => removeIndicator(rowData.description)}
            />
          </div>
        )}
      />
    </DataTable>
  );
};

const Indicators = ({
  element,
  setData,
  activeMetadata,
  mode = 'select',
  availableIndicators,
  readOnly,
}) => {
  const [description, setDescription] = useState('');
  const [unit, setUnit] = useState('');
  const [baselineValue, setBaselineValue] = useState('');
  const [baselineType, setBaselineType] = useState('');
  const [targetValue, setTargetValue] = useState('');
  const [targetType, setTargetType] = useState('');
  const [selectedIndicators, setSelectedIndicators] = useState(
    element ? activeMetadata?.indicators || [] : [],
  );

  const shouldShowForm = () => {
    if (mode === 'select') {
      return false;
    }
    return true;
  };

  const addIndicator = () => {
    setSelectedIndicators(
      selectedIndicators.concat({
        description,
        unit,
        baselineValue,
        baselineType,
        targetValue,
        targetType,
      }),
    );
    setDescription('');
    setUnit('');
    setBaselineValue('');
    setBaselineType('');
    setTargetValue('');
    setTargetType('');
  };

  const removeIndicatorByDescription = (d) => {
    setSelectedIndicators(selectedIndicators.filter((i) => i.description !== d));
  };

  useEffect(() => {
    if (element) {
      setData(element.id, 'indicators', selectedIndicators);
    }
  }, [selectedIndicators]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setSelectedIndicators(activeMetadata?.indicators || []);
    }
  }, [element]); // eslint-disable-line

  return (
    <>
      {shouldShowForm() && (
        <>
          <div className="p-grid p-formgrid p-fluid">
            <div className="p-col-12">
              <div className="p-field">
                <InputText
                  disabled={readOnly}
                  value={description}
                  onChange={(e) => setDescription(e.target.value)}
                  placeholder="Indicator description"
                />
              </div>
            </div>
            <div className="p-col-12">
              <div className="p-field">
                <InputText
                  id="measurementUnit"
                  disabled={readOnly}
                  value={unit}
                  placeholder="Unit of measurement"
                  onChange={(e) => setUnit(e.target.value)}
                />
              </div>
            </div>
          </div>
          <div className="p-grid p-formgrid">
            <div className="p-col-12 p-md-6">
              <div className="p-field">
                <label htmlFor="baseline">Baseline</label>
                <InputText
                  value={baselineValue}
                  disabled={readOnly}
                  onChange={(e) => setBaselineValue(e.target.value)}
                  placeholder="Value"
                  className="w-full"
                />
              </div>
            </div>
            <div className="p-col-12 p-md-6">
              <div className="p-field">
                <label htmlFor="baseline">&nbsp;</label>
                <InputText
                  placeholder="Year"
                  value={baselineType}
                  disabled={readOnly}
                  onChange={(e) => setBaselineType(e.target.value)}
                  className="w-full"
                />
              </div>
            </div>
          </div>
          <div className="p-grid p-formgrid">
            <div className="p-col-12 p-md-6">
              <div className="p-field">
                <label htmlFor="target">Target</label>
                <InputText
                  value={targetValue}
                  disabled={readOnly}
                  onChange={(e) => setTargetValue(e.target.value)}
                  placeholder="Value"
                  className="w-full"
                />
              </div>
            </div>
            <div className="p-col-12 p-md-6">
              <div className="p-field">
                <label htmlFor="target">&nbsp;</label>
                <InputText
                  placeholder="Year"
                  value={targetType}
                  disabled={readOnly}
                  onChange={(e) => setTargetType(e.target.value)}
                  className="w-full"
                />
              </div>
            </div>
          </div>
          <div className="p-grid p-formgrid p-mb-2">
            <div className="p-col-12 p-text-right">
              <Button
                disabled={description.length === 0 || readOnly}
                label="Add"
                onClick={() => addIndicator()}
              />
            </div>
          </div>
        </>
      )}
      {mode === 'select' && (
        <IndicatorsCheckboxes
          availableIndicators={availableIndicators}
          selectedIndicators={selectedIndicators}
          setSelectedIndicators={setSelectedIndicators}
          readOnly={readOnly}
        />
      )}
      {mode !== 'select' && (
        <IndicatorsTable
          readOnly={readOnly}
          indicators={selectedIndicators}
          removeIndicator={removeIndicatorByDescription}
        />
      )}
    </>
  );
};

export default Indicators;
