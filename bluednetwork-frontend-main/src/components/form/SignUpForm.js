import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Formik } from "formik";
import usePasswordToggle from "./usePasswordToggle";
import { FcGoogle } from "react-icons/fc";
import { FaFacebookF } from "react-icons/fa";
import "./Form.css";
import { Link } from "react-router-dom";
import { register } from "../../redux/actions/auth";
import { socialMediaAuth, login } from "../../redux/actions/auth";
// import GoogleLogin from "react-google-login";
import { GoogleLogin } from '@react-oauth/google';

import FacebookLogin from "react-facebook-login";
import { FaSpinner } from 'react-icons/fa';
import { Spinner } from "reactstrap";
import useVirtualAccount from "./hooks/useVirtualAccount";

import {GoogleButton} from 'react-google-button'
import { UserAuth } from "../../context/AuthContext";

const SignUpForm = () => {
  const dispatch = useDispatch();
  const {handleCreateVirtualAccount} = useVirtualAccount()
  const auth = useSelector((state) => state.auth);
  const spinner =  (auth.loading);

  const [PasswordInputType, ToggleIcon] = usePasswordToggle();

  // const responseGoogle = (response) => {
  //   console.log("google", response);
  //   const payload = { code: [response.credential], provider: ["google"] };
  //   console.log("payload", payload);
  //   handleCreateVirtualAccount(response?.profileObj?.email)
  //   dispatch(socialMediaAuth(payload));
  // };

  // const responseFacebook = (response) => {
  //   console.log("facebook", response);
  //   const payload = { code: [response.accessToken], provider: ["facebook"] };
  //   console.log("payload", payload);
  //   dispatch(socialMediaAuth(payload));
  // };
  const {googleSignIn, user} = UserAuth()

  // const [res, setRes] = useState()

  const handleGoogleSignIn = async () => {
    
    try{
        await googleSignIn()
      
      //  console.log(res, 'ressy');
    }
    catch(error) {
      console.log(error);
    }
  }
  return (
    <div className='form-wrapper'>
      <div className='top-icons my-3'>
        <div className='google'>
          <GoogleButton onClick={handleGoogleSignIn}/>
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
        initialValues={{
          first_name: "",
          last_name: "",
          username: "",
          email: "",
          phone_number: "",
          password: "",
          password_confirmation: "",
        }}
        validate={(values) => {
          const errors = {};
          if (!values.email) {
            errors.email = "Required";
          } else if (
            !/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i.test(values.email)
          ) {
            errors.email = "Invalid email address";
          }

          return errors;
        }}
        onSubmit={(values, { setSubmitting }) => {
          setTimeout(() => {
            dispatch(register(values));
            // alert(JSON.stringify(values, null, 2));
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
                name='first_name'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.first_name}
                placeholder='First Name'
                className='mb-3'
                required
              />
              {/* {errors.email && touched.email && errors.email} */}
            </div>
            <div>
              <input
                type='text'
                name='last_name'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.last_name}
                placeholder='Last Name'
                className='mb-3'
                required
              />
              {/* {errors.email && touched.email && errors.email} */}
            </div>
            <div>
              <input
                type='text'
                name='username'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.username}
                placeholder='Username'
                className='mb-3'
                required
              />
              {/* {errors.email && touched.email && errors.email} */}
            </div>
            <div>
              <input
                type='email'
                name='email'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.email}
                placeholder='Email'
                className='mb-3'
                required
              />
              {errors.email && touched.email && errors.email}
            </div>
            <div>
              <input
                type='tel'
                name='phone_number'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.phone_number}
                placeholder='Phone Number'
                className='mb-3'
                required
              />
              {/* {errors.email && touched.email && errors.email} */}
            </div>
            <div className='signup-password-input'>
              <input
                type={PasswordInputType}
                name='password'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.password}
                placeholder='Password'
                className='mb-3'
                required
              />
              <span className='password-toogle-icon'>{ToggleIcon}</span>
              {errors.password && touched.password && errors.password}
            </div>
            <div className='signup-password-input'>
              <input
                type={PasswordInputType}
                name='password_confirmation'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.password_confirmation}
                placeholder='Password Confirmation'
                className='mb-3'
                required
              />
              <span className='password-toogle-icon'>{ToggleIcon}</span>
              {/* {errors.password && touched.password && errors.password} */}
            </div>

            <div className='signup-route-link'>
              <p>
                Already Have an Account? <Link to='/login' className='recover-link'>Login</Link>
              </p>
            </div>

            <div className='login-btn'>
              {/* <button type='submit' disabled={isSubmitting}>
                Create Account
              </button> */}
              {!spinner && (<button type='submit' disabled={isSubmitting}>
              Create Account
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

export default SignUpForm;
