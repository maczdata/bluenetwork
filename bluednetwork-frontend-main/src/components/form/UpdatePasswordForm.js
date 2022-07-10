import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Formik } from "formik";
import usePasswordToggle from "./usePasswordToggle";
import "./Form.css";
import { Link, useLocation, useParams } from "react-router-dom";
import { updatePassword } from "../../redux/actions/auth";

const UpdatePasswordForm = () => {
  const dispatch = useDispatch()
  const [PasswordInputType, ToggleIcon] = usePasswordToggle();

  const { search } = useLocation();
  // console.log(search.split("&"))
  const searchSplit = search.split("&");
  const token = searchSplit[0].split("=")[1];
  // console.log("token", token)
  const email = searchSplit[1].split("=")[1];
  console.log({ token, email });

  return (
    <div className='form-wrapper'>
      <Formik
        initialValues={{ password: "", password_confirmation: "" }}
        onSubmit={(values, { setSubmitting }) => {
          setTimeout(() => {
            // alert(JSON.stringify(values, null, 2));
            const payload = {...values, token, email}
            console.log(payload)
            dispatch(updatePassword(payload))
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
            <div className='password-input'>
              <input
                type={PasswordInputType}
                name='password'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.password}
                placeholder='New Password'
              />
              <span className='password-toogle-icon'>{ToggleIcon}</span>
              {/* {errors.password && touched.password && errors.password} */}
            </div>
            <div className='password-input'>
              <input
                type={PasswordInputType}
                name='password_confirmation'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.password_confirmation}
                placeholder='Confirm Password'
              />
              <span className='password-toogle-icon'>{ToggleIcon}</span>
              {/* {errors.password2 && touched.password2 && errors.password2} */}
            </div>

            <div className='login-btn'>
              <button type='submit' disabled={isSubmitting}>
                Update Password
              </button>
            </div>
          </form>
        )}
      </Formik>
    </div>
  );
};

export default UpdatePasswordForm;
