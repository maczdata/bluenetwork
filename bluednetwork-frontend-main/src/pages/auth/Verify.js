import React from "react";
// import VerifyForm from "../../components/form/VerifyForm";
import "./auth.css";
// import AuthSideCol from "./AuthSideCol";
import { TiTick } from "react-icons/ti";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";

const Verify = () => {
  return (
    <>
      <Header />
      <div>
        <div className='verified__page'>
          <div className='col-md-12 second-section text-center'>
            <div className='tick'>
              <TiTick className='tick-icon' />
            </div>
            <div className='verified__msg my-3'>
              <p>Congratulations!!!!!</p>
              <p>Your account has been verified</p>
              <p>Please log in to access your dashboard </p>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

export default Verify;
