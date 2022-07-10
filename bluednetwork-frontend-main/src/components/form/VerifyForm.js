import React, { useState } from "react";
import { Formik } from "formik";
import usePasswordToggle from "./usePasswordToggle";
import "./Form.css";
import { Link } from "react-router-dom";

const VerifyForm = () => {
  const [formData, setFormData] = useState({
    one: "",
    two: "",
    three: "",
    four: "",
    five: "",
    six: "",
  });

  const { one, two, three, four, five, six } = formData;

  const handleChange = (e) => {
    const re = /^[0-9\b]+$/;
    if (e.target.value === "" || re.test(e.target.value)) {
      setFormData({
        ...formData,
        [e.target.name]: e.target.value,
      });
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log(formData);
  };

  return (
    <div className='form-wrapper'>
      <form onSubmit={handleSubmit} className='row'>
        <div className='password-input col-2'>
          <input
            type='text'
            name='one'
            onChange={(e) => handleChange(e)}
            value={one}
            placeholder=''
          />
        </div>
        <div className='password-input col-2'>
          <input
            type='text'
            name='two'
            onChange={handleChange}
            value={two}
            placeholder=''
          />
        </div>
        <div className='password-input col-2'>
          <input
            type='text'
            name='three'
            onChange={handleChange}
            value={three}
            placeholder=''
          />
        </div>
        <div className='password-input col-2'>
          <input
            type='text'
            name='four'
            onChange={handleChange}
            value={four}
            placeholder=''
          />
        </div>
        <div className='password-input col-2'>
          <input
            type='text'
            name='five'
            onChange={handleChange}
            value={five}
            placeholder=''
          />
        </div>
        <div className='password-input col-2'>
          <input
            type='text'
            name='six'
            onChange={handleChange}
            value={six}
            placeholder=''
          />
        </div>

        <div className='signup-route-link'>
          <p>
            Didn’t receive a code? <Link to='#'>Resend Code</Link>
          </p>
        </div>

        <div className='login-btn'>
          <button type='submit'>Verify</button>
        </div>
      </form>
    </div>
  );
};

export default VerifyForm;
