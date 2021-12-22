import { nanoid } from 'nanoid';
import { AutoComplete } from 'primereact/autocomplete';
import { Button } from 'primereact/button';
import { InputTextarea } from 'primereact/inputtextarea';
import React from 'react';
import { useTranslation } from 'react-i18next';
import NoPovertyImage from '../../assets/img/sdg-indicators/E-WEB-Goal-01.png';
import ZeroHunger from '../../assets/img/sdg-indicators/E-WEB-Goal-02.png';
import GoodHealth from '../../assets/img/sdg-indicators/E-WEB-Goal-03.png';
import QualityEducation from '../../assets/img/sdg-indicators/E-WEB-Goal-04.png';
import GenderEquality from '../../assets/img/sdg-indicators/E-WEB-Goal-05.png';
import CleanWater from '../../assets/img/sdg-indicators/E-WEB-Goal-06.png';
import CleanEnergy from '../../assets/img/sdg-indicators/E-WEB-Goal-07.png';
import DecentWork from '../../assets/img/sdg-indicators/E-WEB-Goal-08.png';
import Innovation from '../../assets/img/sdg-indicators/E-WEB-Goal-09.png';
import ReducedInequalities from '../../assets/img/sdg-indicators/E-WEB-Goal-10.png';
import SustainableCities from '../../assets/img/sdg-indicators/E-WEB-Goal-11.png';
import ResponsibleConsumption from '../../assets/img/sdg-indicators/E-WEB-Goal-12.png';
import ClimateAction from '../../assets/img/sdg-indicators/E-WEB-Goal-13.png';
import LifeBelowWater from '../../assets/img/sdg-indicators/E-WEB-Goal-14.png';
import LifeOnLand from '../../assets/img/sdg-indicators/E-WEB-Goal-15.png';
import PeaceJustice from '../../assets/img/sdg-indicators/E-WEB-Goal-16.png';
import Partnerships from '../../assets/img/sdg-indicators/E-WEB-Goal-17.png';
import FunderList from '../lists/FunderList';
import PartnerList from '../lists/PartnerList';
import TocActionAreas from './TocActionAreas';
import CGIARInitiative from './TocCGIAR';
import TocRelatedSDGs from './TocRelatedSDGs';

const sdgIndicators = [
  { id: 1, name: 'No Poverty', src: NoPovertyImage },
  { id: 2, name: 'Zero Hunger', src: ZeroHunger },
  { id: 3, name: 'Good Health and Well-Being', src: GoodHealth },
  { id: 4, name: 'Quality Education', src: QualityEducation },
  { id: 5, name: 'Gender Equality', src: GenderEquality },
  { id: 6, name: 'Clean Water and Sanitation', src: CleanWater },
  { id: 7, name: 'Affordable and Clean Energy', src: CleanEnergy },
  { id: 8, name: 'Decent Work and Economic Growth', src: DecentWork },
  { id: 9, name: 'Industry, Innovation and Infrastructure', src: Innovation },
  { id: 10, name: 'Reduced Inequalities', src: ReducedInequalities },
  { id: 11, name: 'Sustainable Cities and Communities', src: SustainableCities },
  {
    id: 12,
    name: 'Responsible Consumption and Production',
    src: ResponsibleConsumption,
  },
  { id: 13, name: 'Climate Action', src: ClimateAction },
  { id: 14, name: 'Life below Water', src: LifeBelowWater },
  { id: 15, name: 'Life on Land', src: LifeOnLand },
  { id: 16, name: 'Peace, Justice and Strong Institutions', src: PeaceJustice },
  { id: 17, name: 'Partnerships for the Goals', src: Partnerships },
];

const TocFlowInfo = ({
  title,
  description,
  actionAreas,
  selectedActionAreas,
  funders,
  partners,
  sustainableDevelopmentGoals,
  cgiarInitiative,
  type,
  showDescription,

  setTitle,
  setDescription,
  setFunders,
  setPartners,
  setCgiarInitiative,
  setType,
  toggleSDGIndicator,
  toggleSelectedActionArea,

  onSubmit,
  mode = 'add',
}) => {
  const { t } = useTranslation();

  return (
    <>
      <div className="p-fluid p-grid p-justify-start">
        <div className="p-col-12 p-md-8 p-lg-5">
          <div className="p-field">
            <label htmlFor="title">
              {t('PROJECT_TITLE')}
              <span className="required">*</span>
            </label>
            <AutoComplete
              id="title"
              value={title}
              onChange={(e) => setTitle(e.value)}
              field="title"
            />
          </div>
          <div className="p-field-checkbox p-d-flex p-ai-center">
            <CGIARInitiative
              cgiarInitiative={cgiarInitiative}
              setCgiarInitiative={setCgiarInitiative}
              type={type}
              setType={setType}
            />
          </div>
        </div>
      </div>
      {showDescription && (
        <div className="p-fluid p-grid p-justify-start">
          <div className="p-col-12 p-md-8 p-lg-5">
            <div className="p-field">
              <label htmlFor="project-title">{t('PROJECT_DESCRIPTION')}</label>
              <InputTextarea
                style={{ resize: 'none' }}
                rows={3}
                value={description || ''}
                onChange={(e) => setDescription(e.target.value)}
              />
            </div>
          </div>
        </div>
      )}
      {cgiarInitiative && (
        <TocActionAreas
          actionAreas={actionAreas}
          selectedActionAreas={selectedActionAreas}
          toggleSelectedActionArea={toggleSelectedActionArea}
        />
      )}
      <div className="p-fluid p-grid p-justify-start">
        <div className="p-col-12 p-md-8 p-lg-5">
          <div className="p-field p-mb-0">
            <label htmlFor="project-funders">
              {t('PROJECT_FUNDERS')}
              <span
                tabIndex={-1}
                role="button"
                onClick={() => setFunders(funders.concat({ id: nanoid(), orgId: 0, orgName: '' }))}
                className="badge rounded-full p-ml-2 cursor-pointer text-white"
              >
                +
              </span>
            </label>
          </div>
          <FunderList items={funders} setItems={setFunders} />
        </div>
      </div>
      {mode === 'none' && (
        <>
          <div className="p-fluid p-grid p-justify-start">
            <div className="p-col-12 p-md-8 p-lg-5">
              <div className="p-field p-mb-0">
                <label htmlFor="project-partners">
                  {t('PROJECT_PARTNERS')}
                  <span
                    tabIndex={-1}
                    role="button"
                    onClick={() =>
                      setPartners(partners.concat({ id: nanoid(), orgId: 0, orgName: '' }))
                    }
                    className="badge rounded-full p-ml-2 cursor-pointer text-white"
                  >
                    +
                  </span>
                </label>
              </div>
              <PartnerList items={partners} setItems={setPartners} />
            </div>
          </div>
          <TocRelatedSDGs
            sdgIndicators={sdgIndicators}
            sustainableDevelopmentGoals={sustainableDevelopmentGoals}
            toggleSDGIndicator={toggleSDGIndicator}
          />
        </>
      )}
      {onSubmit && (
        <div className="p-grid p-justify-start p-mt-0 p-pb-6">
          <div className="p-col-12 p-md-8 p-lg-6">
            <div className="p-d-flex p-jc-start p-ai-center">
              <Button label={t('SAVE')} onClick={onSubmit} />
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default TocFlowInfo;
