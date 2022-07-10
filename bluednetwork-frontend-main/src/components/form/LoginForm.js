import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom";
// import GoogleLogin from "react-google-login";
import FacebookLogin from "react-facebook-login";
import { useDispatch, useSelector } from "react-redux";
import { Formik } from "formik";
import usePasswordToggle from "./usePasswordToggle";
import { FcGoogle } from "react-icons/fc";
import { FaFacebookF } from "react-icons/fa";
import { FaSpinner } from 'react-icons/fa';
import "./Form.css";
import { Link } from "react-router-dom";
import { socialMediaAuth, login, socialMediaAuthReact } from "../../redux/actions/auth";

import { Spinner } from "reactstrap";

// import {
//   GoogleButton,
//   IAuthorizationOptions,
//   isLoggedIn,
//   createOAuthHeaders,
//   logOutOAuthUser,
//   GoogleAuth,
// } from "react-google-oauth2";

import { GoogleLogin } from '@react-oauth/google';

import { useGoogleLogin } from '@react-oauth/google';

import {GoogleButton} from 'react-google-button'

import { UserAuth } from "../../context/AuthContext";

const LoginForm = () => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const spinner =  (auth.loading);

  console.log("spinner", spinner)

  const [PasswordInputType, ToggleIcon] = usePasswordToggle();

  const responseGoogle = (response) => {
    console.log("google", response);
    const payload = { code: [response?.code], provider: ["google"] };
    console.log("payload", payload);
    dispatch(socialMediaAuthReact(payload));
  };

  const responseFacebook = (response) => {
    console.log("facebook", response);
    const payload = { code: [response.accessToken], provider: ["facebook"] };
    console.log("payload", payload);
    dispatch(socialMediaAuth(payload));
  };

  // const loggedIn = useGoogleLogin({
  //   onSuccess: responseGoogle,
  //   onError: responseGoogle,
  //   flow: 'auth-code',
  // });
  const {googleSignIn, user} = UserAuth()

  const [res, setRes] = useState()

  const handleGoogleSignIn = async () => {
    
    try{
        await googleSignIn()
      
      //  console.log(res, 'ressy');
    }
    catch(error) {
      console.log(error);
    }
  }

  // useEffect(()=>{
  //   if (user) {
        
  //   } 
  // }, [user])

  
  return (
    <div className='form-wrapper'>
      <div className='top-icons my-3'>
        <div className='google'>
          <GoogleButton onClick={handleGoogleSignIn}/>
          {/* <button onClick={()=> loggedIn()}>
            Sign in with Google 🚀{' '}
          </button> */}
        </div>
        {/* <div className='facebook'>
          <FacebookLogin
            appId='266715515196343'
            // autoLoad={false}
            fields='name,email,picture'
            callback={responseFacebook}
            cssClass='my-facebook-button-class'
            // icon='fa-facebook'
            textButton={
              <span>
                <FaFacebookF className='icom' />
              </span>
            }
          />
        </div> */}
      </div>
      <div className='horizontal'>
        <div className='horizontal-line'></div>
        <p className=''>or</p>
        <div className='horizontal-line'></div>
      </div>
      <Formik
        initialValues={{ identity: "", password: "" }}
        onSubmit={(values, { setSubmitting }) => {
          setTimeout(() => {
            dispatch(login(values));
            console.log(values, spinner);
            setSubmitting(false);
          }, 400);
          console.log(values);
        }}
      >
        {({
          values,
          errors,
          touched,
          handleChange,
          handleBlur,
          handleSubmit,
          isSubmitting,
          /* and other goodies */
        }) => (
          <form onSubmit={handleSubmit}>
            <div>
              <input
                type='text'
                name='identity'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.identity}
                placeholder='Username'
                required
              />
              {/* {errors.email && touched.email && errors.email} */}
            </div>
            <div className='password-input'>
              <input
                type={PasswordInputType}
                name='password'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.password}
                placeholder='Password'
                required
              />
              <span className='password-toogle-icon'>{ToggleIcon}</span>
              {/* {errors.password && touched.password && errors.password} */}
            </div>
            <div className='recover'>
              <span>
                Forgot Password?{" "}
                <Link to='/recover-password' className='recover-link'>
                  Recover Password
                </Link>{" "}
              </span>
            </div>

            <div className='signup-route-link'>
              <p>
                Don’t Have an Account? <Link to='/sign-up' className='recover-link'>Sign Up</Link>
              </p>
            </div>

            <div className='login-btn'>
              {!spinner && (<button type='submit' disabled={isSubmitting}>
                Login
              </button>)
              }
              {
              spinner && (<button type='submit' disabled>
                <Spinner color='light' />
              </button>)}
            </div>
          </form>
        )}
      </Formik>
    </div>
  );
};

export default LoginForm;

