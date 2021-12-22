import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css';
import { Button } from 'primereact/button';
import 'primereact/resources/primereact.min.css';
import { Toast } from 'primereact/toast';
import React, { useContext, useEffect, useRef, useState } from 'react';
import { Redirect, useLocation } from 'react-router-dom';
import MelLogo from '../assets/img/login-with-mel.png';
import Loading from '../components/Loading';
import { verify } from '../services/auth';
import { UserContext } from '../store';

const Login = () => {
  const [isLoading, setIsLoading] = useState(false);
  const { isLoggedIn, setUser } = useContext(UserContext);
  const { search } = useLocation();
  const toast = useRef();

  const redirectToMEL = async () => {
    window.location.href = process.env.REACT_APP_MEL_REDIRECT_URL;
  };

  useEffect(() => {
    const authorizationCode = new URLSearchParams(search).get('code');
    if (authorizationCode && !isLoggedIn) {
      setIsLoading(true);
      verify(authorizationCode)
        .then(({ data }) => {
          setUser({
            user: data.user,
            access_token: data.access_token,
            profile: data.user_mel_profile,
            isLoggedIn: true,
          });
          setIsLoading(false);
        })
        .catch((error) => {
          // TODO: Handle the errors from the backend properly.
          if (toast.current) {
            toast.current.show({
              severity: 'error',
              summary: 'Oops!',
              detail: 'Failed to authenticate user!',
            });
          }
          setIsLoading(false);
        });
    }
  }, []); // eslint-disable-line react-hooks/exhaustive-deps

  if (isLoggedIn) {
    return <Redirect to="/" />;
  }

  if (isLoading) {
    return <Loading />;
  }

  return (
    <>
      <Toast ref={toast} position="top-right" />
      <div className="login-wrapper">
        <div className="login-body">
          <div className="p-grid h-100" style={{ margin: '0' }}>
            <div className="p-col-12 p-md-6 p-lg-4 h-100 p-d-flex p-flex-column p-jc-center p-ai-center">
              <Button
                type="button"
                onClick={redirectToMEL}
                className="p-button p-component p-button-outlined p-button-secondary login-with-mel-btn"
                loading={isLoading}
              >
                <img src={MelLogo} alt="Login With MEL" className="p-mr-2" />
                <span className="p-button-label p-c">Login with MEL</span>
              </Button>
              <p className="p-mt-6 p-text-center">
                For any information about this tool please contact{' '}
                <a href="mailto:mel-support@cgiar.org">mel-support@cgiar.org</a>
              </p>
            </div>
            <div className="login-bg p-col-12 p-md-6 p-lg-8 p-d-flex p-jc-center p-ai-center h-100">
              <h1>Access the Theory of Change Tool</h1>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Login;
