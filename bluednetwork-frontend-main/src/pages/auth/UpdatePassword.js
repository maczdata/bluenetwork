import React from "react";
import "./auth.css";
import UpdatePasswordForm from "../../components/form/UpdatePasswordForm";
import AuthSideCol from "./AuthSideCol";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";

const UpdatePassword = () => {
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
              <h1>Set New Password</h1>
              <p>Set a new password for your account</p>
              <div className='login-icons'>
                <UpdatePasswordForm />
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

export default UpdatePassword;
