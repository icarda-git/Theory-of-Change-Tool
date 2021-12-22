import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import { Button } from 'primereact/button';
import { Checkbox } from 'primereact/checkbox';
import { InputText } from 'primereact/inputtext';
import { InputTextarea } from 'primereact/inputtextarea';
import 'primereact/resources/primereact.min.css';
import React, { useContext, useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Redirect } from 'react-router-dom';
import Logo from '../components/Logo';
import { UserContext } from '../store';

const Register = () => {
  // TODO: Default false.
  // eslint-disable-next-line
  const { t } = useTranslation();
  const [, setFormValid] = useState(true);
  const [acceptedTerms, setAcceptedTerms] = useState(false);
  // eslint-disable-next-line
  const [firstName, setFirstName] = useState('');
  // eslint-disable-next-line
  const [lastName, setLastName] = useState('');
  const [email, setEmail] = useState('');
  const [emailConfirm, setEmailConfirm] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirm, setPasswordConfirm] = useState('');
  const [reason, setReason] = useState('');
  const userImage = useRef(null);
  const user = useContext(UserContext);

  useEffect(() => {
    // TODO: For register button to be enabled form also needs to be valid.
    setFormValid(true);

    // TODO: For error handling later on.
    if (password !== passwordConfirm) {
      setFormValid(false);
    }

    if (email !== emailConfirm) {
      setFormValid(false);
    }
  }, [password, passwordConfirm, email, emailConfirm]);

  if (user.token) {
    return <Redirect to="/" />;
  }

  return (
    <>
      <div className="register-page">
        <Logo />
        <div className="p-d-flex p-jc-center p-ai-center">
          <div className="layout-content layout-content-fixed">
            <div className="p-grid">
              <div className="p-col-12 p-md-8">
                <div className="card p-fluid p-shadow-4 rounded">
                  <h5>{t('NEW_USER_REGISTRATION_TEXT')}</h5>
                  <div className="p-formgrid p-grid p-mt-3">
                    <div className="p-field p-col-12 p-md-6">
                      <label htmlFor="firstname">{t('FIRSTNAME')}</label>
                      <InputText
                        id="firstname"
                        type="text"
                        onChange={(e) => setFirstName(e.target.value)}
                        required
                      />
                    </div>
                    <div className="p-field p-col-12 p-md-6">
                      <label htmlFor="lastname">{t('LASTNAME')}</label>
                      <InputText
                        id="lastname"
                        onChange={(e) => setLastName(e.target.value)}
                        type="text"
                        required
                      />
                    </div>
                    <div className="p-field p-col-12 p-md-6">
                      <label htmlFor="email">{t('EMAIL')}</label>
                      <InputText
                        id="email"
                        type="email"
                        onChange={(e) => setEmail(e.target.value)}
                        required
                      />
                    </div>
                    <div className="p-field p-col-12 p-md-6">
                      <label htmlFor="emailConfirm">{t('REPEAT_EMAIL')}</label>
                      <InputText
                        id="emailConfirm"
                        onChange={(e) => setEmailConfirm(e.target.value)}
                        type="email"
                        required
                      />
                    </div>
                    <div className="p-field p-col-12 p-md-6">
                      <label htmlFor="password">{t('PASSWORD')}</label>
                      <InputText
                        id="password"
                        onChange={(e) => setPassword(e.target.value)}
                        type="password"
                      />
                    </div>
                    <div className="p-field p-col-12 p-md-6">
                      <label htmlFor="passwordConfirm">{t('REPEAT_PASSWORD')}</label>
                      <InputText
                        id="passwordConfirm"
                        onChange={(e) => setPasswordConfirm(e.target.value)}
                        type="password"
                      />
                    </div>
                    <div className="p-field p-col-12">
                      <label htmlFor="reason">{t('REASON_FOR_REGISTRATION')}</label>
                      <InputTextarea
                        id="reason"
                        rows={5}
                        value={reason}
                        onChange={(e) => setReason(e.target.value)}
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div className="p-col-12 p-md-4">
                {' '}
                <div className="card p-fluid p-shadow-4 rounded">
                  <h5>{t('PROFILE_PICTURE_TITLE')}</h5>
                  <div className="p-formgrid p-grid">
                    <div className="p-field p-col-12 p-text-center">
                      <img
                        src="https://picsum.photos/140/140"
                        height="138px"
                        className="rounded-full"
                        alt="Avatar"
                      />
                    </div>
                    <div className="p-field p-col-12">
                      <Button
                        label="Select image"
                        icon="pi pi-image"
                        className="p-button-secondary p-mr-2 p-mb-2"
                        onClick={() => {
                          userImage.current.click();
                        }}
                      />
                    </div>
                    <form>
                      <input type="file" id="file" ref={userImage} style={{ display: 'none' }} />
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div className="p-col-12 p-text-center p-mt-1">
              <div className="p-mb-5">
                <Checkbox
                  inputId="legalCheck"
                  name="option"
                  checked={acceptedTerms}
                  onChange={() => {
                    setAcceptedTerms(!acceptedTerms);
                  }}
                />
                <label className="p-ml-2" htmlFor="legalCheck" style={{ cursor: 'pointer' }}>
                  {t('TERMS_ACCEPT')}
                </label>
              </div>
              <Button
                label={t('CREATE_ACCOUNT_BUTTON')}
                icon="pi pi-user-plus"
                disabled={acceptedTerms === false}
                className="p-button-big p-mr-2 p-mb-2"
              />
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Register;
