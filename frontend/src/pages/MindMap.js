import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import 'primereact/resources/primereact.min.css';
import React from 'react';
import { useParams } from 'react-router-dom';
import DiagramEditor from '../components/diagram-editor';
import { useQuery } from '../utilities/hooks';

const AVAILABLE_FLAVOURS = ['initiative-level', 'work-package-level'];

const Mindmap = () => {
  let flavour = 'initiative-level';
  const { flowId, tocId } = useParams();
  const query = useQuery();

  if (AVAILABLE_FLAVOURS.includes(query.get('flavour'))) {
    flavour = query.get('flavour');
  }

  const readOnly = query.get('readOnly') || false;

  return <DiagramEditor flavour={flavour} tocId={tocId} flowId={flowId} readOnly={readOnly} />;
};

export default Mindmap;
