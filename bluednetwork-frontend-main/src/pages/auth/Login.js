import React from "react";
import "./auth.css";
import LoginForm from "../../components/form/LoginForm";
import AuthSideCol from "./AuthSideCol";
import { Redirect } from "react-router-dom";
import { useSelector } from "react-redux";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import "react-toastify/dist/ReactToastify.css";

const Login = () => {
  const auth = useSelector((state) => state.auth);
  const { data, isAuthenticated } = auth;

  if (isAuthenticated) {
    return <Redirect to='/app/dashboard' />;
  }

  return (
    <>
      <Header />
      <div className='auth-wrapper'>
        <div className='row'>
          <div className='col-md-6 first-section'>
            <AuthSideCol />
          </div>
          <div className='col-md-6 second-section'>
            <div className='login-form'>
              <h1>Login</h1>
              <div className='login-icons'>
                <LoginForm />
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

export default Login;
