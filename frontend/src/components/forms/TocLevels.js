import { nanoid } from 'nanoid';
import { Checkbox } from 'primereact/checkbox';
import React from 'react';
import { useTranslation } from 'react-i18next';
import WorkPackagesList from '../lists/WorkPackagesList';

const TocLevels = ({
  cgiarInitiative,
  initiativeLevel,
  setInitiativeLevel,
  workPackageLevel,
  setWorkPackageLevel,
  workPackages,
  setWorkPackages,
  edit,
}) => {
  const { t } = useTranslation();

  return (
    <>
      <div className="p-my-3">
        {!edit && (
          <div className="p-grid">
            <div className="p-col-12">
              <label htmlFor="title">{t('TOC_LEVEL')}</label>
            </div>
          </div>
        )}
        {!edit && (
          <div className="p-grid p-formgrid p-justify-start">
            <div className="p-col-12 p-md-8 p-lg-5">
              <div className="p-field-checkbox p-d-flex p-ai-center">
                <Checkbox
                  inputId="initiative-level"
                  name="option"
                  checked={initiativeLevel}
                  disabled
                  onChange={(e) => setInitiativeLevel(e.checked)}
                />
                <label htmlFor="initiative-level">Initiative/Project Level (n-1).</label>
              </div>
            </div>
          </div>
        )}
        {!edit && (
          <div className="p-grid p-formgrid p-justify-start">
            <div className="p-col-12 p-md-8 p-lg-5">
              <div className="p-field-checkbox p-d-flex p-ai-center">
                <Checkbox
                  inputId="work-package-level"
                  name="option"
                  checked={workPackageLevel}
                  disabled={cgiarInitiative}
                  onChange={(e) => setWorkPackageLevel(e.checked)}
                />
                <label htmlFor="work-package-level">Work Package level (n-2).</label>
              </div>
            </div>
          </div>
        )}
        {workPackageLevel && (
          <div className="p-grid p-justify-start">
            <div className="p-col-12 p-md-8 p-lg-5">
              <div className="p-field p-my-1">
                <label htmlFor="work-packages">
                  {t('WORK_PACKAGES')}
                  <span
                    tabIndex={-1}
                    role="button"
                    onClick={() =>
                      setWorkPackages(
                        workPackages.concat({
                          id: nanoid(),
                          type: 'work-package-level',
                          number:
                            workPackages[workPackages.length - 1]?.number !== undefined
                              ? workPackages[workPackages.length - 1]?.number + 1
                              : 1,
                          title: '',
                        }),
                      )
                    }
                    className="badge rounded-full p-ml-2 cursor-pointer text-white"
                  >
                    +
                  </span>
                </label>
              </div>
              <WorkPackagesList items={workPackages} setItems={setWorkPackages} />
            </div>
          </div>
        )}
      </div>
    </>
  );
};

export default TocLevels;
