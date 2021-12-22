import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import en from '../translations/en/index';

i18n.use(initReactI18next).init({
  resources: { en },
  lng: 'en',
  keySeparator: false,
  fallbackLng: 'en',
  lowerCaseLng: true,
  cleanCode: true,
  interpolation: { escapeValue: false },
});

export default i18n;
