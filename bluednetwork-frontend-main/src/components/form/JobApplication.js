import React, { useState } from "react";
import { Formik } from "formik";
import Dropzone from "react-dropzone";

import "./Form.css";

import DragNDropIcon from "../../assets/drag-drop-icon.svg";

const JobApplication = ({ applyBtnStyle }) => {
  const [fileNames, setFileNames] = useState([]);
  const handleDrop = (acceptedFiles) =>
    setFileNames(acceptedFiles.map((file) => file.name));

  return (
    <div className="form-wrapper">
      <Formik
        initialValues={{ email: "", password: "" }}
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
            <div className="job-app-form-fields-wrap">

            {/* Full name */}
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

            {/* email */}
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
              {errors.email && touched.email && errors.email}
            </div>

            {/* Phone number */}
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
            </div>

            {/* Handle drag and drop */}
            <Dropzone
              onDrop={handleDrop}
              accept="document/pdf"
              minSize={1024}
              maxSize={3072000}
            >
              {({
                getRootProps,
                getInputProps,
                isDragActive,
                isDragAccept,
                isDragReject,
              }) => {
                const additionalClass = isDragAccept
                  ? "accept"
                  : isDragReject
                  ? "reject"
                  : "";

                return (
                  <div
                    {...getRootProps({
                      className: `dropzone ${additionalClass}`,
                    })}
                  >
                    <div className="drag-drop-area">
                      <input {...getInputProps()} />
                      <span className="my-4">
                        {isDragActive ? (
                          <img src={DragNDropIcon} alt="" />
                        ) : (
                          <img src={DragNDropIcon} alt="" />
                        )}
                      </span>
                      <p>Drag or Click to Upload your Resume (PDF)</p>
                    </div>
                  </div>
                );
              }}
            </Dropzone>
            <div>
              <ul>
                {fileNames.map((fileName) => (
                  <li key={fileName}>{fileName}</li>
                ))}
              </ul>
            </div>

            <Dropzone
              onDrop={handleDrop}
              accept="document/pdf"
              minSize={1024}
              maxSize={3072000}
            >
              {({
                getRootProps,
                getInputProps,
                isDragActive,
                isDragAccept,
                isDragReject,
              }) => {
                const additionalClass = isDragAccept
                  ? "accept"
                  : isDragReject
                  ? "reject"
                  : "";

                return (
                  <div
                    {...getRootProps({
                      className: `dropzone ${additionalClass}`,
                    })}
                  >
                    <div className="drag-drop-area">
                      <input {...getInputProps()} />
                      <span className="my-4">
                        {isDragActive ? (
                          <img src={DragNDropIcon} alt="" />
                        ) : (
                          <img src={DragNDropIcon} alt="" />
                        )}
                      </span>
                      <p>Drag or Click to Upload your cover letter (PDF)</p>
                    </div>
                  </div>
                );
              }}
            </Dropzone>
            <div>
              <ul>
                {fileNames.map((fileName) => (
                  <li key={fileName}>{fileName}</li>
                ))}
              </ul>
            </div>

            {/* call to action - Btn */}
            <div>
              <button
                className={applyBtnStyle}
                type="submit"
                disabled={isSubmitting}
              >
                Apply
              </button>
            </div>
          </form>
        )}
      </Formik>
    </div>
  );
};

export default JobApplication;
