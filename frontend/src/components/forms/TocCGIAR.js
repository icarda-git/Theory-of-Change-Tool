import { SelectButton } from 'primereact/selectbutton';
import React from 'react';
import cgiarLogoWhite from '../../assets/img/cgiar-logo-white.png';
import cgiarLogo from '../../assets/img/cgiar-logo.png';

const options = [
  { label: 'Project', value: 'project' },
  { label: 'Proposal', value: 'proposal' },
];

const CGIARInitiative = ({ cgiarInitiative, setCgiarInitiative, type, setType }) => (
  <>
    <span
      tabIndex={0}
      role="button"
      className={
        cgiarInitiative
          ? `p-button p-togglebutton p-component p-highlight cursor-pointer`
          : `p-button p-text-center p-togglebutton p-component cursor-pointer`
      }
      style={{ width: 'auto' }}
      onClick={() => setCgiarInitiative(!cgiarInitiative)}
    >
      <img
        src={cgiarInitiative ? cgiarLogoWhite : cgiarLogo}
        title="CGIAR Initiative"
        alt="CGIAR Logo"
        className="w-35px"
      />
    </span>
    <div className="p-ml-4">
      <SelectButton value={type} options={options} onChange={(e) => setType(e.value)} />
    </div>
  </>
);

export default CGIARInitiative;
