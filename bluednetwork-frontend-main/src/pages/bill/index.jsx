import React from "react";
import { Link } from "react-router-dom";
import "./index.css";
import HeroHomeImg from "../../assets/cuatechilling-lady.svg";
import Reciept from "../../assets/reciept.svg";
import Data from "../../assets/changes/data.png";
import TV from "../../assets/changes/tv.png";
import Electricity from "../../assets/changes/electric.png";
import Airtime from "../../assets/changes/airtime.png";
import ArrowRight from "../../assets/arrow-right.svg";
import Partners from "../../components/partners/Partners";
import Footer from "../../components/footer/Footer";
import Header from "../../components/header/Header";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";

const Bill = () => {
  return (
    <>
      <Header />
      <div>
        {/* Hero section */}
        <div className='container my-5'>
          <div className='row fix-spacing'>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>BD Bills</h1>
              <p className=''>
                If you live in Nigeria, you already know it’s tough out here,
                and the bills keep skyrocketing. We’ve come up with a way that
                lets you pay your bills with ease, and at cheaper prices. Simply
                pick up your phone, and do so with a smile on your face.
              </p>
              <CtaBtn />
            </div>
            <div className='col-md-6'>
              <img src={Reciept} alt='img' className='img-fluid' />
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={Airtime} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 display-on-small-screen mb-5'>
              <img src={Airtime} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Airtime</h1>
              <p className=''>
                If you live in Nigeria, you already know it’s tough out here,
                and the bills keep skyrocketing. We’ve come up with a way that
                lets you pay your bills with ease, and at cheaper prices. Simply
                pick up your phone, and do so with a smile on your face.
              </p>

              <button className='one-btn mt-3'>Proceed</button>
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 mb-5 display-on-small-screen'>
              <img src={Data} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Data</h1>
              <p className=''>
                If you live in Nigeria, you already know it’s tough out here,
                and the bills keep skyrocketing. We’ve come up with a way that
                lets you pay your bills with ease, and at cheaper prices. Simply
                pick up your phone, and do so with a smile on your face.
              </p>

              <button className='one-btn mt-3'>Proceed</button>
            </div>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={Data} alt='img' className='img-fluid' />
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6  hide-on-small-screen '>
              <img src={TV} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 display-on-small-screen mb-5'>
              <img src={TV} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Television</h1>
              <p className=''>
                If you live in Nigeria, you already know it’s tough out here,
                and the bills keep skyrocketing. We’ve come up with a way that
                lets you pay your bills with ease, and at cheaper prices. Simply
                pick up your phone, and do so with a smile on your face.
              </p>

              <button className='one-btn my-4'>Proceed</button>
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 display-on-small-screen'>
              <img src={Electricity} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Electricity</h1>
              <p className=''>
                If you live in Nigeria, you already know it’s tough out here,
                and the bills keep skyrocketing. We’ve come up with a way that
                lets you pay your bills with ease, and at cheaper prices. Simply
                pick up your phone, and do so with a smile on your face.
              </p>

              <button className='one-btn my-4'>Proceed</button>
            </div>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={Electricity} alt='img' className='img-fluid' />
            </div>
          </div>
        </div>
        {/* <div className='bg-utility'>
          <div className='container utility mb-5 pb-5'>
            <div></div>
            <div className='utility-contents'>
              <div className='utility-contents-five'>
                <img src={Reciept} alt='img' className='img-fluid' />
              </div>
              <div className='utility-contents-seven'>
                <div className='d-flex flex-column flex-md-row'>
                  <div className='housing'>
                    <div className='card p-4 mb-5'>
                      <div className='mx-auto'>
                        <img src={Services} alt='img' className='' />
                      </div>
                      <div className='d-flex justify-content-between align-items-center mt-auto'>
                        <div className='name'>Data Services</div>
                        <div className=' arrow p-2 d-flex justify-content-between align-items-center'>
                          <img
                            src={ArrowRight}
                            alt='img'
                            className='img-fluid'
                          />
                        </div>
                      </div>
                    </div>
                    <div className='card p-4'>
                      <div className='mx-auto'>
                        <img src={TV} alt='img' className='' />
                      </div>
                      <div className='d-flex justify-content-between align-items-center mt-auto'>
                        <div className='name'>TV Subcriptiion</div>
                        <div className=' arrow p-2 d-flex justify-content-between align-items-center'>
                          <img
                            src={ArrowRight}
                            alt='img'
                            className='img-fluid'
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className=' mt-5 pt-5'>
                    <div className='card p-4 mb-5'>
                      <div className='mx-auto'>
                        <img src={Airtime} alt='img' className='' />
                      </div>
                      <div className='d-flex justify-content-between align-items-center mt-auto'>
                        <div className='name'>Airtime</div>
                        <div className=' arrow p-2 d-flex justify-content-between align-items-center'>
                          <img
                            src={ArrowRight}
                            alt='img'
                            className='img-fluid'
                          />
                        </div>
                      </div>
                    </div>
                    <div className='card p-4'>
                      <div className='mx-auto'>
                        <img src={Electricity} alt='img' className='' />
                      </div>
                      <div className='d-flex justify-content-between align-items-center mt-auto'>
                        <div className='name'>Electricity</div>
                        <div className=' arrow p-2 d-flex justify-content-between align-items-center'>
                          <img
                            src={ArrowRight}
                            alt='img'
                            className='img-fluid'
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> */}

        {/* Empowered Section */}
        <Ecosystem />
        {/* BD exchange section */}

        {/* <Partners /> */}
      </div>
      <Footer />
    </>
  );
};

export default Bill;
