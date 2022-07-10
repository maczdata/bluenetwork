import React from "react";
import "./index.css";
import BrandHeroImg from "../../assets/branding.svg";
import Diamonds9 from "../../assets/Diamonds (1).svg";
import Diamonds10 from "../../assets/Diamonds (2).svg";
import QuoteForm from "../../components/form/QuoteForm";
import Services from "../../components/Services/index";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";
import Data from "../../assets/changes/rrr.png";
import TV from "../../assets/changes/ttt.png";
import Electricity from "../../assets/changes/bro.png";
import Airtime from "../../assets/changes/airtime.png";

const Graphics = () => {
  const quoteFormStyle = {
    background: "#000152",
    boxShadow: "0px 6px 15px rgba(26, 33, 107, 0.15)",
    borderRadius: "100px",
    border: "none",
    fontStyle: "normal",
    fontWeight: "600",
    fontSize: "18px",
    lineHeight: "30px",
    textAlign: "center",
    color: "#ffffff",
    padding: "10px 20px",
    width: "100%",
    marginTop: "15px",
  };
  return (
    <>
      <Header />
      <div>
        <div className='container mt-5'>
          <div className='row fix-spacing'>
            <div className='col-md-6 hero-graphics-intro add_pd'>
              <h1 className='fw-bold'>BD Branding</h1>
              <p>
                Say goodbye to poor branding, sloppy designs and grim
                advertising; it’s time to turn the heat up for your business. We
                want that for you just as much as you do.
              </p>
              <CtaBtn />
            </div>
            <div className='col-md-6 mt-5 mt-lg-0'>
              <img src={BrandHeroImg} alt='img' className='img-fluid' />
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={TV} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 display-on-small-screen'>
              <img src={TV} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Graphics Design</h1>
              <p className=''>
                Say goodbye to poor branding, sloppy designs and grim
                advertising; it’s time to turn the heat up for your business. We
                want that for you just as much as you do.
              </p>
              <button className='one-btn my-4'>Proceed</button>
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 display-on-small-screen'>
              <img src={Data} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>CAC Registration</h1>
              <p className=''>
                Say goodbye to poor branding, sloppy designs and grim
                advertising; it’s time to turn the heat up for your business. We
                want that for you just as much as you do.
              </p>
              <button className='one-btn my-4'>Proceed</button>
            </div>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={Data} alt='img' className='img-fluid' />
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={Electricity} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 display-on-small-screen'>
              <img src={Electricity} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Branding Combo</h1>
              <p className=''>
                Say goodbye to poor branding, sloppy designs and grim
                advertising; it’s time to turn the heat up for your business. We
                want that for you just as much as you do.
              </p>
              <button className='one-btn my-4'>Proceed</button>
            </div>
          </div>
        </div>
        {/* Another Section */}
        {/* <div className='container'>
          <Services />
        </div> */}
        {/* Get Quote Section */}
        <div className='graphics-quote-wrapper'>
          <h1 className='fw-bold text-center pt-5'>REQUEST QUOTE</h1>
          <div className='graphics-quote-wrapper-img'>
            <img src={Diamonds9} alt='' className='left-side img-fluid' />
            <img src={Diamonds10} alt='' className='right-side img-fluid' />
          </div>
          <div className='graphics-quote-form'>
            <QuoteForm
              ctaBtnText={"Get Quote"}
              ctaBtnStyle={quoteFormStyle}
              formDescription={
                "To get a quick quote for your project, kindly fill the form below."
              }
            />
          </div>
        </div>
      </div>
      {/* Empowered Section */}
      <Ecosystem />

      <Footer />
    </>
  );
};

export default Graphics;
