import { Button } from 'primereact/button';
import { Menubar } from 'primereact/menubar';
import React from 'react';
import genderDimension from '../../../assets/img/gender-balance.png';

const getMenuItems = ({
  saveAsPng,
  saveNewVersion,
  saveFlow,
  addNode,
  editSelectedNode,
  deleteSelectedNode,
  fitView,
  selectedElement,
  publish,
}) => [
  {
    label: 'File',
    items: [
      {
        label: 'Save as New Version',
        icon: 'pi pi-fw pi-save',
        command: () => saveNewVersion(),
      },
      {
        label: 'Export as PNG',
        icon: 'pi pi-fw pi-download',
        command: () => saveAsPng(),
      },
      {
        label: 'Publish',
        icon: 'pi pi-fw pi-upload',
        command: () => publish(),
      },
    ],
  },
  {
    icon: 'pi pi-fw pi-search',
    label: 'Reset View',
    command: () => fitView(),
  },
  {
    icon: 'pi pi-fw pi-save',
    command: () => saveFlow(),
  },
  {
    template: (
      <span
        role="button"
        className={selectedElement ? 'p-menuitem-link p-mr-2' : 'p-menuitem-link p-mr-2 opacity-25'}
        tabIndex={0}
        onClick={() => deleteSelectedNode()}
      >
        <i className="pi pi-times" />
      </span>
    ),
  },
  {
    template: (
      <span
        title="Add End Of Iniative Outcome"
        role="button"
        className="p-menuitem-link p-mr-2"
        tabIndex={0}
        style={{
          display: 'flex',
          cursor: 'pointer',
          width: '53px',
          height: '35px',
          justifyContent: 'center',
          alignItems: 'center',
          background: '#103A97',
          color: 'white',
        }}
        onClick={() =>
          addNode('endOfInitiativeOutcome', {
            description: 'End Of Initiative Outcome',
          })
        }
      >
        EOI
      </span>
    ),
  },
  {
    template: (
      <span
        title="Add Work Package Outcome"
        role="button"
        className="p-menuitem-link p-mr-2"
        tabIndex={0}
        style={{
          display: 'flex',
          cursor: 'pointer',
          alignItems: 'center',
          justifyContent: 'center',
          height: '35px',
          background: '#929292',
          color: 'white',
        }}
        onClick={() => addNode('workPackageOutcome', { description: 'Outcome' })}
      >
        Outcome
      </span>
    ),
  },
  {
    template: (
      <span
        title="Add Work Package Output"
        role="button"
        className="p-menuitem-link p-mr-2"
        tabIndex={0}
        style={{
          display: 'flex',
          cursor: 'pointer',
          height: '35px',
          alignItems: 'center',
          justifyContent: 'center',
          background: '#6F9A4F',
          color: 'white',
        }}
        onClick={() => addNode('workPackageOutput', { description: 'Output' })}
      >
        Output
      </span>
    ),
  },
  {
    template: (
      <span
        role="button"
        className="p-menuitem-link p-mr-2"
        tabIndex={0}
        style={{
          display: 'flex',
          cursor: 'pointer',
          alignItems: 'center',
          justifyContent: 'center',
          width: '90px',
          height: '35px',
          background: '#DFDFDF',
        }}
        onClick={() => {
          editSelectedNode((s) => ({
            ...s,
            data: {
              ...s.data,
              hasgenderDimension: !s.data.hasgenderDimension,
            },
          }));
        }}
      >
        <img src={genderDimension} className="p-ml-2" alt="Gender Dimension" height="25px" />
        <span className="p-mx-2">Gender</span>
      </span>
    ),
  },
];

const WorkPackageLevelMenubar = ({
  saveFlow,
  saveAsPng,
  saveNewVersion,
  sidebarVisible,
  toggleSidebarVisible,
  addNode,
  editSelectedNode,
  deleteSelectedNode,
  fitView,
  selectedElement,
  publish,
}) => {
  const sidebarButton = () => (
    <Button
      onClick={() => toggleSidebarVisible(!sidebarVisible)}
      label="Toggle Sidebar"
      icon="pi pi-sliders-h"
      className="p-button p-component p-button-text p-button-plain"
    />
  );

  return (
    <Menubar
      style={{ zIndex: 10 }}
      model={getMenuItems({
        saveAsPng,
        saveNewVersion,
        saveFlow,
        addNode,
        editSelectedNode,
        deleteSelectedNode,
        fitView,
        selectedElement,
        publish,
      })}
      end={sidebarButton}
    />
  );
};

export default WorkPackageLevelMenubar;
