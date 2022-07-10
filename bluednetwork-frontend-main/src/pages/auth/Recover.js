import React from "react";
import "./auth.css";
import RecoverForm from "../../components/form/RecoverForm";
import AuthSideCol from "./AuthSideCol";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";

const Recover = () => {
  return (
    <>
      <Header />
      <div>
        <div className='row'>
          <div className='col-md-6 first-section'>
            <AuthSideCol />
          </div>
          <div className='col-md-6 second-section'>
            <div className='login-form'>
              <h1>Recover Password</h1>
              <p>
                Enter your email address or phone number and we’ll send you a
                password reset link. You can reset your password from this link.
              </p>
              <div className='login-icons'>
                <RecoverForm />
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

export default Recover;
