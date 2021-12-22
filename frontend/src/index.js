import React from 'react';
import 'react-app-polyfill/ie11';
import ReactDOM from 'react-dom';
import { HashRouter } from 'react-router-dom';
import AppWrapper from './AppWrapper';
import './utilities/i18n-next';

ReactDOM.render(
  <HashRouter>
    <AppWrapper />
  </HashRouter>,
  document.getElementById('root'),
);
