import React from 'react';
import { useTranslation } from 'react-i18next';

const TocActionAreas = ({ actionAreas, selectedActionAreas, toggleSelectedActionArea }) => {
  const { t } = useTranslation();

  return (
    <div className="p-fluid p-grid p-justify-start">
      <div className="p-col-12 p-md-8 p-lg-5">
        <div className="p-field p-mb-0">
          <label htmlFor="action-areas">
            {t('ACTION_AREAS')}
            <span className="required">*</span>
          </label>
          <div className="p-d-flex">
            {actionAreas.map((area) => (
              <div
                key={area.code}
                role="button"
                tabIndex="0"
                className="p-d-inline-block cursor-pointer relative p-mr-3"
                onClick={() => toggleSelectedActionArea(Number(area.code))}
              >
                {selectedActionAreas.includes(Number(area.code)) && (
                  <div
                    className="p-d-flex p-jc-center p-ai-center"
                    style={{
                      position: 'absolute',
                      top: '0',
                      left: '0',
                      width: '100%',
                      height: '100%',
                      backgroundColor: 'rgba(0, 0, 0, 0.5)',
                      zIndex: '99',
                    }}
                  >
                    <i className="pi pi-check" style={{ fontSize: '2rem', color: 'white' }} />
                  </div>
                )}
                <img src={area.image} alt={area.title} className="w-125px" />
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default TocActionAreas;
