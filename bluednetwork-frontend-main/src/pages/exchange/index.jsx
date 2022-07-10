import React from "react";
import { Link } from "react-router-dom";
import "./index.css";
import HeroHomeImg from "../../assets/changes/exchanges.png";
import Exchange from "../../assets/Giftcard-exchange.svg";
import Exchanges from "../../assets/changes/giftcard.png";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";

const Bill = () => {
  return (
    <>
      <Header />
      {/* Hero section */}
      <div className='container '>
        <div className='row fix-spacing'>
          <div className='col-md-6 hero-home-intro add_pd'>
            <h1>BD Exchange</h1>
            {/* <p className=''>
              Enough of the unsafe trading and shady platforms. You can sell
              your gift cards to us with your eyes closed, and be assured of
              good rates, and fast payments.
            </p> */}
            <CtaBtn />
          </div>
          <div className='col-md-6'>
            {/* <img src={HeroHomeImg} alt='img' className='img-fluid' /> */}
            <img src={HeroHomeImg} alt='img' className='img-fluid' />
          </div>
        </div>

        <div className='row fix-spacing'>
          <div className='col-md-6 hide-on-small-screen'>
            <img src={Exchanges} alt='img' className='img-fluid' />
          </div>
          <div className='col-md-6 display-on-small-screen'>
            <img src={Exchanges} alt='img' className='img-fluid' />
          </div>
          {/* <div className='col-md-6 hero-home-intro add_pd'>
            <h2>Exchange Giftcards</h2>
            <p className=''>
              Enough of the unsafe trading and shady platforms. You can sell
              your gift cards to us with your eyes closed, and be assured of
              good rates, and fast payments.
            </p>

            <button className='one-btn mt-3'>Trade Now</button>
          </div> */}
        </div>

        <div className='row fix-spacing'>
          <div className='col-md-6 display-on-small-screen'>
            <img src={Exchange} alt='img' className='img-fluid' />
          </div>
          <div className='col-md-6 hero-home-intro add_pd'>
            <h2>Exchange Airtime for Instant Cash</h2>
            <h4 className='mt-4'>GOT EXCESS AIRTIME</h4>
            <p>
              Convert your airtime to cash instantly in 5minutes, or all
              networks in Nigeria. You'll get the best rate for your airtime. At
              Blue-D Services, airtime is money.
            </p>

            <button className='one-btn mt-3'>Trade Now</button>
          </div>
          <div className='col-md-6 mt-5 hide-on-small-screen'>
            <img src={Exchange} alt='img' className='img-fluid' />
          </div>
        </div>
      </div>

      {/* <div className="diamon-pattern ">
        <img src={Diamonds} alt='img' className='img-fluid' />
      </div> */}
      {/* <div className='bg-utility-con'>
        <div className='container utility mb-5 pb-5'>
          <div className='bd-exchange-home'>
            <div className='row'>
              <div className='col-md-6 my-auto'>
                <img src={Exchange} alt='img' className='img-fluid' />
              </div>
              <div className='col-md-6 hero-home-intro'>
                <h2>Exchange Airtime for Instant Cash</h2>
                <h4 className='mt-4'>GOT EXCESS AIRTIME</h4>
                <p>
                  Convert your airtime to cash instantly in 5minutes, or all
                  networks in Nigeria. You'll get the best rate for your
                  airtime. At Blue-D Services, airtime is money.
                </p>

                <button className='one-btn'>Trade Now</button>
              </div>
            </div>
          </div>
        </div>
      </div> */}
      {/* Empowered Section */}
      <Ecosystem />

      <Footer />
    </>
  );
};

export default Bill;
