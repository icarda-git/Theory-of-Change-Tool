import { nanoid } from 'nanoid';
import { Button } from 'primereact/button';
import { Checkbox } from 'primereact/checkbox';
import { Dropdown } from 'primereact/dropdown';
import { InputText } from 'primereact/inputtext';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Link } from 'react-router-dom';
import { createToc, getTocs } from '../../services/flows';
import PendingRequestsList from '../lists/PendingRequestsList';
import TeamCompositionList from '../lists/TeamCompositionList';

const levels = [
  { label: 'Initiative Level', value: 'initiative-level' },
  { label: 'Work Package Level', value: 'work-package-level' },
];

const TocFlowTeamComposition = ({
  id,

  participants,
  pendingRequests,
  participatoryDevelopment,
  participatoryDevelopmentURL,
  level,
  tocs,
  showAll,

  setParticipants,
  setPendingRequests,
  setParticipatoryDevelopment,
  setParticipatoryDevelopmentURL,
  setLevel,
  setTocs,

  onSubmit,
}) => {
  const { t } = useTranslation();
  const [levelOptions, setLevelOptions] = useState(levels);

  useEffect(() => {
    if (!tocs) return;
    tocs.forEach(({ toc_type: type }) => {
      if (type === 'initiative-level') {
        setLevelOptions(levels.filter((l) => l.value !== 'initiative-level'));
      }
    });
  }, []); // eslint-disable-line

  useEffect(() => {
    if (!tocs) return;
    tocs.forEach(({ toc_type: type }) => {
      if (type === 'initiative-level') {
        setLevelOptions(levels.filter((l) => l.value !== 'initiative-level'));
      }
    });
  }, [tocs]); // eslint-disable-line

  const createNewToc = async () => {
    try {
      await createToc(id, level, generateTitle(level));
      const { data } = await getTocs(id);
      setTocs(data);
    } catch (error) {
      setTocs([]);
    }
  };

  const generateTitle = (tocType) => {
    const workPackagesCount = tocs.filter(
      ({ toc_type: type }) => type === 'work-package-level',
    ).length;

    if (tocType === 'initiative-level') {
      return 'Initiative Level';
    }

    if (tocType === 'work-package-level') {
      const c = workPackagesCount + 1;
      return `Work Package Level ${c}`;
    }

    return '';
  };

  return (
    <>
      <div className="p-fluid p-grid p-justify-start">
        <div className="p-col-12 p-md-8 p-lg-6">
          <div className="p-field p-mb-0">
            <label htmlFor="participants">
              {t('CONTRIBUTORS')}
              <span
                tabIndex={-1}
                role="button"
                onClick={() =>
                  setParticipants(participants.concat({ id: nanoid(), email: '', role: '' }))
                }
                className="badge rounded-full p-ml-2 cursor-pointer text-white"
              >
                +
              </span>
            </label>
          </div>
          <TeamCompositionList items={participants} setItems={setParticipants} />
        </div>
      </div>
      {showAll && (
        <div className="p-fluid p-grid p-justify-start">
          <div className="p-col-12 p-md-8 p-lg-6">
            <div className="p-field p-mb-0">
              <label htmlFor="pending-requests">{t('PENDING_REQUESTS')}</label>
            </div>
            <PendingRequestsList items={pendingRequests} setItems={setPendingRequests} />
          </div>
        </div>
      )}
      <div className="p-fluid p-grid p-justify-start">
        <div className="p-col-12 p-md-8 p-lg-6">
          <div className="p-field-checkbox p-d-flex p-ai-center">
            <Checkbox
              inputId="participatoryDevelopment"
              name="option"
              checked={participatoryDevelopment}
              onChange={(e) => setParticipatoryDevelopment(e.checked)}
            />
            <label htmlFor="participatoryDevelopment">Enable Participatory Development Board</label>
          </div>
          {participatoryDevelopment && (
            <div className="p-field">
              <InputText
                id="participatoryDevelopmentURL"
                name="participatoryDevelopmentURL"
                value={participatoryDevelopmentURL}
                placeholder="Enter the URL for participatory development"
                onChange={(e) => setParticipatoryDevelopmentURL(e.target.value)}
              />
            </div>
          )}
        </div>
      </div>
      {showAll && (
        <div className="p-fluid p-grid p-justify-start">
          <div className="p-col-12 p-md-8 p-lg-6">
            <div className="p-field">
              <label htmlFor="toc-levels">TOCs</label>
              <ul className="p-mt-0 p-mb-4">
                {tocs &&
                  tocs.map(({ title, id: tocId, toc_type: tocType }) => (
                    <li key={tocId}>
                      <Link to={`/flows/${id}/tocs/${tocId}?flavour=${tocType}`}>
                        {title} {tocType && <span>({tocType})</span>}
                      </Link>
                    </li>
                  ))}
              </ul>
            </div>
            <div className="p-field">
              <div className="p-grid p-formgrid">
                <div className="p-col-8">
                  <Dropdown
                    id="toc-levels"
                    value={level}
                    options={levelOptions}
                    placeholder="Select TOC type"
                    onChange={(e) => setLevel(e.target.value)}
                  />
                </div>
                <div className="p-col-4">
                  <Button label="Create TOC" onClick={() => createNewToc()} />
                </div>
              </div>
            </div>
          </div>
        </div>
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

export default TocFlowTeamComposition;
