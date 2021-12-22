/* eslint-disable import/prefer-default-export */
export const getProgrammeTypeId = (cgiar, project, proposal) => {
  const m = {
    'CGIAR Initiative': 1,
    'CGIAR Project': 2,
    'CGIAR Proposal': 3,
    Project: 4,
    Proposal: 5,
  };
  if (cgiar && project) {
    return m['CGIAR Project'];
  }
  if (cgiar && proposal) {
    return m['CGIAR Proposal'];
  }
  if (proposal) {
    return m.Proposal;
  }
  if (project) {
    return m.Project;
  }
  if (cgiar) {
    return m['CGIAR Initiative'];
  }
  return 0;
};

export const GetCGIARInitiativeFromProgrameId = (typeId) => [1, 2, 3].includes(typeId);
export const GetProjectOrProposal = (typeId) => {
  if ([2, 4].includes(typeId)) {
    return 'project';
  }
  if ([3, 5].includes(typeId)) {
    return 'proposal';
  }
  return null;
};

// TODO: Remove this and keep the backend mappings once we are good there.
export const getTocType = (tocType) => {
  if (tocType.toLowerCase().includes('initiative')) {
    return 'initiative-level';
  }
  if (tocType.toLowerCase().includes('work')) {
    return 'work-package-level';
  }
  return 'initiative-level';
};

export const saveAs = (blob, fileName) => {
  const elem = window.document.createElement('a');
  elem.href = blob;
  elem.download = fileName;
  elem.style = 'display:none;';
  (document.body || document.documentElement).appendChild(elem);
  if (typeof elem.click === 'function') {
    elem.click();
  } else {
    elem.target = '_blank';
    elem.dispatchEvent(
      new MouseEvent('click', {
        view: window,
        bubbles: true,
        cancelable: true,
      }),
    );
  }
  URL.revokeObjectURL(elem.href);
  elem.remove();
};

export const validate = ({
  workPackageLevel,
  workPackages,
  cgiarInitiative,
  type,
  selectedActionAreas,
}) => {
  if (workPackageLevel && workPackages.length === 0) {
    throw new Error('Please add at least one work package.');
  }
  if (
    cgiarInitiative &&
    (type === '' || type == null) &&
    (selectedActionAreas.length === 0 || selectedActionAreas.length > 1)
  ) {
    if (selectedActionAreas.length > 1) {
      throw new Error('Please select only one action area.');
    }
    throw new Error('Please select one action area.');
  }
  if (cgiarInitiative && selectedActionAreas.length === 0 && type !== '' && type != null) {
    throw new Error('Please select one or more action areas.');
  }
};
