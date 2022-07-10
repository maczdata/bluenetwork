import React from "react";
import "./index.css";
import HeroWebDesign from "../../assets/Web-design.svg";
import Diamonds9 from "../../assets/Diamonds (1).svg";
import Diamonds10 from "../../assets/Diamonds (2).svg";
import QuoteForm from "../../components/form/QuoteForm";
import ServiceVector from "../../assets/service-card-vector.svg";
import ArrowRight from "../../assets/white-right-arrow.svg";
import IMG1 from "../../assets/Diamonds (3).svg";
import Footer from "../../components/footer/Footer";
import Header from "../../components/header/Header";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";
import Data from "../../assets/changes/smm.png";
import TV from "../../assets/changes/webd.png";
import Electricity from "../../assets/changes/electric.png";
import Airtime from "../../assets/changes/cw.png";

const Web = () => {
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
      <div className='web-container-wrapper'>
        <div className='container mt-5'>
          <div className='row fix-spacing'>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>BD Web</h1>
              <p className=''>
                Our job is to help you create a functional and responsive
                website that fits your need and keeps your customers interested.
                Let us begin this fantastic journey together!
              </p>
              <CtaBtn />
            </div>
            <div className='col-md-6 hero-home-img'>
              <img src={HeroWebDesign} alt='img' className='img-fluid' />
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
              <h1>Website design</h1>
              <p className=''>
                Our job is to help you create a functional and responsive
                website that fits your need and keeps your customers interested.
                Let us begin this fantastic journey together!
              </p>

              <button className='one-btn my-4'>Proceed</button>
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 display-on-small-screen'>
              <img src={Data} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Social media management</h1>
              <p className=''>
                Our job is to help you create a functional and responsive
                website that fits your need and keeps your customers interested.
                Let us begin this fantastic journey together!
              </p>

              <button className='one-btn my-4'>Proceed</button>
            </div>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={Data} alt='img' className='img-fluid' />
            </div>
          </div>

          <div className='row fix-spacing'>
            <div className='col-md-6 hide-on-small-screen'>
              <img src={Airtime} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 display-on-small-screen'>
              <img src={Airtime} alt='img' className='img-fluid' />
            </div>
            <div className='col-md-6 hero-home-intro add_pd'>
              <h1>Copywriting</h1>
              <p className=''>
                Our job is to help you create a functional and responsive
                website that fits your need and keeps your customers interested.
                Let us begin this fantastic journey together!
              </p>

              <button className='one-btn my-4'>Proceed</button>
            </div>
          </div>
        </div>

        {/* <div className='web-service-img'>
          <img src={IMG1} alt='' />
        </div> */}

        {/* Another Section */}
        {/* <div className='container web-service-cards'> */}
        {/* <Services /> */}
        {/* <div className='service-cards'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>WEB DESIGN</h2>
                <p className=''>
                  Now your website is up and running, attractive and
                  user-friendly. However, there’s still more work to do. Search
                  Engine Optimization is what boosts your website traffic.
                  Without this, you may not get as much traffic as you would
                  love...
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div> */}

        {/* <Services /> */}
        {/* <div className='service-cards'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>WEB CONTENT WRITTING</h2>
                <p className=''>
                  Since the internet is close to everyone’s favourite place to
                  shop, find information or find blogs, you will have to make
                  sure your site is one of the places they’re going Web content
                  writing makes this possible...
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div> */}

        {/* <Services /> */}
        {/* <div className='service-cards'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>SOCIAL MEDIA MANAGEMENT</h2>
                <p className=''>
                  Another highly effective way to boost brand awareness is the
                  use of social media. It is almost impossible to have good
                  brand awareness without social media these days. This is not
                  limited to small business owners or larger businesses.
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div> */}
        {/* </div> */}

        {/* Get Quote Section */}
        <div className='graphics-quote-wrapper'>
          <h1 className='fw-bold text-center pt-5'>Request A Quote</h1>
          <div className='graphics-quote-wrapper-img'>
            <img src={Diamonds9} alt='' className='left-side img-fluid' />
            <img src={Diamonds10} alt='' className='right-side img-fluid' />
          </div>
          <div className='graphics-quote-form'>
            <QuoteForm
              ctaBtnText={"Create an account"}
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

export default Web;
