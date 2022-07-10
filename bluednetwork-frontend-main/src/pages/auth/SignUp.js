import React from "react";
import "./auth.css";
import SignUpForm from "../../components/form/SignUpForm";
import AuthSideCol from "./AuthSideCol";
import { Redirect } from "react-router-dom";
import { useSelector } from "react-redux";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import "react-toastify/dist/ReactToastify.css";

const SignUp = () => {
  const auth = useSelector((state) => state.auth);
  const { data, isAuthenticated } = auth;

  if (isAuthenticated) {
    return <Redirect to='/app/dashboard' />;
  }

  return (
    <>
      <Header />
      <div>
        <div className='row auth-wrapper'>
          <div className='col-md-6 first-section'>
            <AuthSideCol />
          </div>
          <div className='col-md-6 second-section'>
            <div className='login-form'>
              <h1>Create an account</h1>
              <div className='login-icons'>
                <SignUpForm />
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

export default SignUp;
