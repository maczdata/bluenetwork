import React, { useEffect, useState } from "react";
import { Link, Redirect, useLocation, useHistory} from "react-router-dom";
import Footer from "../../components/footer/Footer";
import VerifyForm from "../../components/form/VerifyForm";
import Header from "../../components/header/Header";
import "./auth.css";
import AuthSideCol from "./AuthSideCol";
import Ring from '@bit/joshk.react-spinners-css.ring';
import {} from "react-router-dom"
import axios from "axios";

const VerifyEmail = () => {
  const location = useLocation()

  
  const [err, setErr] = useState()
  const [res, setRes] = useState()
  const [formData, setFormData] = useState({
    one: "",
  });

  const { one } = formData;

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
  const runVerify = async (token, email) => {
    let response
    response = await axios({
      url: `https://api.bluednetwork.com/front_api/verification/verify`,
      method: 'post',
      params: {
        source_value: email,
        verification_token: token,
        verification_source: 'email'
      } 
    }).then((response) => {
      console.log('my res', response);
      setRes(response?.data?.message)
      return <Redirect to={'/app/dashboard'} />
    }).catch((err) => {
      console.log(err?.response)
      setErr(err?.response?.data?.message)
    })
  }
  useEffect(()=>{
    const queryParams = new URLSearchParams(location.search)
    const token = queryParams.get("token")
    const email = queryParams.get("email")
    runVerify(token, email)
  
  }, [])
  return (
    <>
      <Header />
      <div>
        <div className='row'>
          <div className='col-md-6 first-section'>
            <AuthSideCol />
          </div>
          <div className='col-md-6 second-section'>
            <div className='login-form' style={{
              display: 'flex',
              justifyContent: 'center',
              alignItems: 'center'
            }}>
              {!res && !err && <Ring color={'rgba(0, 1, 82, 1)'}/>}
              {err && (
                <h4>
                  {err}
                </h4>
              )}
              {
                res && <h4>{'this' + res}</h4>
              }
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

export default VerifyEmail;
