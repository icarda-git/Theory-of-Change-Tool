import React from 'react';
import { useTranslation } from 'react-i18next';

const TocRelatedSDGs = ({ sdgIndicators, sustainableDevelopmentGoals, toggleSDGIndicator }) => {
  const { t } = useTranslation();

  return (
    <div className="p-grid p-justify-start">
      <div className="p-col-12">
        <div className="p-mb-2">
          <label htmlFor="related-sdgs">{t('RELATED_SDGS')}</label>
        </div>
        {sdgIndicators &&
          sdgIndicators.map(({ id, name, src }) => (
            <div
              key={id}
              className="p-d-inline-flex p-mr-3 p-mb-3"
              role="button"
              style={{ position: 'relative', cursor: 'pointer' }}
              tabIndex={0}
              onClick={() => toggleSDGIndicator(id)}
            >
              {sustainableDevelopmentGoals.includes(Number(id)) && (
                <div
                  className="p-d-flex p-jc-center p-ai-center"
                  style={{
                    position: 'absolute',
                    top: '0',
                    left: '0',
                    width: '100%',
                    height: '100%',
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    zIndex: '99',
                  }}
                >
                  <i className="pi pi-check" style={{ fontSize: '3rem', color: 'white' }} />
                </div>
              )}
              <img src={src} alt={name} title={name} width="125px" height="125px" />
            </div>
          ))}
      </div>
    </div>
  );
};

export default TocRelatedSDGs;
