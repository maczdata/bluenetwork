import React from "react";
import { Formik } from "formik";
import "./Form.css";

const QuoteForm = ({ ctaBtnText, ctaBtnStyle, formTitle, formDescription }) => {
  return (
    <div className="quote-form">
      {formTitle ? (
        <h3 style={{ textAlign: "left", padding: "1em 0" }}>{formTitle}</h3>
      ) : null}
      {formDescription ? (
        <p
          style={{
            margin: ".5em auto",
            padding: ".5em 1em",
            fontWeight: "bold",
          }}
        >
          {formDescription}
        </p>
      ) : null}
      <div className="form-wrapper">
        <Formik
          initialValues={{
            fullName: "",
            email: "",
            mobile: "",
            projectTitle: "",
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
                  type="text"
                  name="fullName"
                  onChange={handleChange}
                  onBlur={handleBlur}
                  value={values.fullName}
                  placeholder="Full Name"
                  className="mb-3"
                />
                {/* {errors.email && touched.email && errors.email} */}
              </div>
              <div>
                <input
                  type="email"
                  name="email"
                  onChange={handleChange}
                  onBlur={handleBlur}
                  value={values.email}
                  placeholder="Email"
                  className="mb-3"
                />
                {/* {errors.email && touched.email && errors.email} */}
              </div>
              <div>
                <input
                  type="tel"
                  name="mobile"
                  onChange={handleChange}
                  onBlur={handleBlur}
                  value={values.mobile}
                  placeholder="Mobile"
                  className="mb-3"
                />
                {/* {errors.email && touched.email && errors.email} */}
              </div>

              <div className="signup-password-input">
                <input
                  type="text"
                  name="projectTitle"
                  onChange={handleChange}
                  onBlur={handleBlur}
                  value={values.projectTitle}
                  placeholder="Project Title"
                  className="mb-3"
                />
                {/* <span className='password-toogle-icon'>{ToggleIcon}</span> */}
                {/* {errors.password && touched.password && errors.password} */}
              </div>
              <div className="signup-password-input">
                <select name="cars" id="cars" className="mb-3">
                  <option value="volvo">Select Category</option>
                  <option value="saab">Airtime</option>
                  <option value="mercedes">Data</option>
                  <option value="audi">Television</option>
                  <option value="mercedes">Electricity</option>
                  <option value="audi">Airtime to cash</option>
                  {/* <option value="mercedes">Giftcard</option> */}
                  <option value="audi">Graphics Design</option>
                  <option value="mercedes">CAC Registration</option>
                  <option value="audi">Branding Combo</option>
                  <option value="mercedes">Website design</option>
                  <option value="audi"> social media management</option>
                  <option value="mercedes">copywriting</option>
                  <option value="audi">Printing and Typesetting</option>
                  <option value="audi">Typesetting</option>
                </select>
              </div>

              <div className="signup-password-input">
                <textarea
                  className="mb-3"
                  id="w3review"
                  name="w3review"
                  rows="4"
                  cols="40"
                  placeholder="Tell us about your project"
                />{" "}
              </div>

              <div>
                <button
                  style={ctaBtnStyle}
                  type="submit"
                  disabled={isSubmitting}
                >
                  {ctaBtnText}
                </button>
              </div>
            </form>
          )}
        </Formik>
      </div>
    </div>
  );
};

export default QuoteForm;
