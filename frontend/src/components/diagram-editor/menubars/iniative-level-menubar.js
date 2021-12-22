import { Button } from 'primereact/button';
import { Menubar } from 'primereact/menubar';
import React, { useState } from 'react';
import cgiarLogo from '../../../assets/img/cgiar-logo.png';
import sdgList from '../../../assets/img/sdg-list.png';

const getMenuItems = ({
  saveAsPng,
  saveFlow,
  saveNewVersion,
  toggleLeftSidebar,
  addNode,
  deleteSelectedNode,
  fitView,
  toggleSidebarOpen,
  sidebar,
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
        role="button"
        className="p-menuitem-link p-mr-2"
        title="Add SDG Indicator"
        tabIndex={0}
        style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          cursor: 'pointer',
          width: '80px',
          height: '40px',
          border: sidebar === 'sdg-indicators' ? '2px solid #2196F3' : 'none',
        }}
        onClick={() => {
          sidebar === 'sdg-indicators'
            ? toggleSidebarOpen('')
            : toggleSidebarOpen('sdg-indicators');
          toggleLeftSidebar('sdg-indicators');
        }}
      >
        <img src={sdgList} alt="SDG List" width="30px" height="30px" />
        <span className="p-ml-2 p-py-2">SDG</span>
      </span>
    ),
  },
  {
    template: (
      <span
        role="button"
        title="Add Impact Area"
        className="p-menuitem-link p-mr-2"
        tabIndex={0}
        style={{
          display: 'flex',
          cursor: 'pointer',
          alignItems: 'center',
          justifyContent: 'center',
          width: '60px',
          height: '40px',
          border: sidebar === 'cgiar' ? '2px solid #2196F3' : 'none',
        }}
        onClick={() => {
          sidebar === 'cgiar' ? toggleSidebarOpen('') : toggleSidebarOpen('cgiar');
          toggleLeftSidebar('cgiar');
        }}
      >
        <img src={cgiarLogo} alt="CGIAR Logo" width="30px" height="30px" />
        <span className="p-ml-2 p-py-2">IA</span>
      </span>
    ),
  },
  {
    template: (
      <span
        title="Add Action Area Outcome"
        role="button"
        className="p-menuitem-link p-mr-2"
        tabIndex={0}
        style={{
          display: 'flex',
          cursor: 'pointer',
          width: '53px',
          height: '35px',
          textAlign: 'center',
          justifyContent: 'center',
          alignItems: 'center',
          background: '#BBB33B',
          border: sidebar === 'action-area-outcomes' ? '2px solid #2196F3' : 'none',
        }}
        onClick={() => {
          sidebar === 'action-area-outcomes'
            ? toggleSidebarOpen('')
            : toggleSidebarOpen('action-area-outcomes');

          toggleLeftSidebar('action-area-outcomes');
        }}
      >
        AA
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
          justifyContent: 'center',
          alignItems: 'center',
          width: '53px',
          height: '35px',
          textAlign: 'center',
          background: '#103A97',
          color: 'white',
        }}
        onClick={() => {
          toggleSidebarOpen('');
          toggleLeftSidebar('');
          addNode('endOfInitiativeOutcome', {
            description: 'End Of Initiative Outcome',
          });
        }}
      >
        EOI
      </span>
    ),
  },
  {
    template: (
      <span
        title="Add Work Package"
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
          background: '#BC30E5',
          color: 'white',
        }}
        onClick={() => {
          toggleSidebarOpen('');
          toggleLeftSidebar('');
          addNode('workPackage', { description: 'Work Package' });
        }}
      >
        WP
      </span>
    ),
  },
];

const IniativeLevelMenubar = ({
  saveFlow,
  saveAsPng,
  saveNewVersion,
  sidebarVisible,
  toggleSidebarVisible,
  toggleLeftSidebar,
  addNode,
  deleteSelectedNode,
  fitView,
  selectedElement,
  publish,
}) => {
  const [sidebar, setSidebar] = useState('');
  const toggleSidebarOpen = (s) => setSidebar(s);

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
        saveFlow,
        toggleLeftSidebar,
        addNode,
        deleteSelectedNode,
        saveNewVersion,
        fitView,
        toggleSidebarOpen,
        sidebar,
        selectedElement,
        publish,
      })}
      end={sidebarButton}
    />
  );
};

export default IniativeLevelMenubar;
