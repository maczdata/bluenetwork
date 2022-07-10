import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Formik } from "formik";
import "./Form.css";
import { Link } from "react-router-dom";
import { forgotPassword } from "../../redux/actions/auth";

const RecoverForm = () => {

  const dispatch = useDispatch()
  
  return (
    <div className='form-wrapper'>
      <Formik
        initialValues={{ email: "" }}
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
            dispatch(forgotPassword(values));
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
                type='email'
                name='email'
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.email}
                placeholder='Email'
              />
              {errors.email && touched.email && errors.email}
            </div>

            <div className='login-btn'>
              {/* <Link to="/update-password"> */}
                <button type='submit' disabled={isSubmitting}>
                  Recover Password
                </button>
              {/* </Link> */}
            </div>
          </form>
        )}
      </Formik>
    </div>
  );
};

export default RecoverForm;
