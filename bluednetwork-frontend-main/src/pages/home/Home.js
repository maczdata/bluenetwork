import React from "react";
import { Link } from "react-router-dom";
import Partners from "../../components/partners/Partners";
import "./Home.css";
// import HeroHomeImg from "../../assets/hero-home-img.svg";
import HeroHomeImg from "../../assets/changes/Hero Section.png";
// import Diamonds from "../../assets/Group 201.svg";
import Diamonds2 from "../../assets/Group 202.svg";
import Diamonds3 from "../../assets/Frame 105.svg";
import Diamonds4 from "../../assets/Group 213.svg";
import Diamonds5 from "../../assets/Group 214.svg";
import Diamonds51 from "../../assets/changes/cuate.png";
import Diamonds6 from "../../assets/Mobile App (1).svg";
import Diamonds from "../../assets/changes/Group 201.png";
import Diamonds7 from "../../assets/Google Play Badge (1).svg";
import Diamonds8 from "../../assets/Apple Play Badge (1).svg";
import Diamonds9 from "../../assets/Diamonds (1).svg";
import Diamonds10 from "../../assets/Diamonds (2).svg";
import ServicesSlider from "../../components/sliders/ServicesSlider/ServicesSlider";
import TestimonySlider from "../../components/sliders/TestimonySlider/TestimonySlider";
import QuoteForm from "../../components/form/QuoteForm";
import Subscribe from "../../components/subscribe/Subscribe";
import Exchange from "../../assets/Giftcard-exchange.svg";
import Bills from "../../assets/pana.svg";
import Branding from "../../assets/branding.svg";
import Web from "../../assets/Web-design (1).svg";
import Collection from "../../assets/collections.svg";
import Avatar from "../../assets/avatar.svg";
import { useState } from "react";
import { collectionData, data, collectionDataQuestion } from "../faq/data";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";
import Faqs from "../../components/faq/Faqs";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import useGetServices from "../../hooks/useGetServices";

const quoteFormStyle = {
  background: "#ff7f00",
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

const Home = () => {
  const [showCollection, setShowCollection] = useState(false);
  const [showCollectionQuestion, setShowCollectionQuestion] = useState(false);
  return (
    <div className='homepage-wrapper'>
      <Header />
      <div className='hero-home'>
        {/* Hero section */}
        <div className='container mt-5'>
          <div className='row'>
            <div className='col-md-6 hero-home-intro'>
              <h1>Get Empowered to Do More!</h1>
              <p className=''>
                With only a few taps on your phone, you get access to a variety
                of services, at delightfully discounted prices. You’ve seen
                nothing like this!
              </p>
              <CtaBtn />
            </div>
            <div className='col-md-6 hero-home-img'>
              <img src={HeroHomeImg} alt='img' className='img-fluid' />
            </div>
          </div>
        </div>
      </div>

      {/* BD exchange section */}
      <div className='bd-exchange-home'>
        <div className='row'>
          <div className='col-md-6 bd-exchange-home-img'>
            <img src={Diamonds} alt='img' className='img-fluid' />
          </div>
          <div className='col-md-6 bd-exchange-home-intro'>
            <h2>BD Exchange</h2>
            <p className=''>
              Searching for a place to exchange your assets for money? Then
              you’re in the right place.
            </p>

            {/* <p className=''>
              On BD Exchange, you get to exchange your excess airtime and your
              Gift cards for money, conveniently and securely. You don’t have to
              worry about payments, they get to your fast!
            </p> */}

            <div className='bd-exchange-home-link'>
              <Link to='/exchange'>Learn more </Link>
            </div>
          </div>
          <div className='col-md-6 display-on-small-screen'>
            <img src={Exchange} alt='img' className='img-fluid' />
          </div>
        </div>
      </div>

      {/* BD bills section */}
      <div className='bd-bills-home'>
        <div className='row'>
          <div className='col-md-6 bd-exchange-home-intro'>
            <h2>BD Bills</h2>
            <p className=''>
              Searching for a place to exchange your assets for money? Then
              you’re in the right place.
            </p>

            {/* <p className=''>
              On BD Exchange, you get to exchange your excess airtime and your
              Gift cards for money, conveniently and securely. You don’t have to
              worry about payments, they get to your fast!
            </p> */}

            <div className='bd-exchange-home-link'>
              <Link to='/bill'>Learn more </Link>
            </div>
          </div>
          <div className='col-md-6 bd-exchange-home-img'>
            <img src={Diamonds2} alt='img' className='img-fluid' />
          </div>
          <div className='col-md-6 display-on-small-screen'>
            <img src={Bills} alt='img' className='img-fluid' />
          </div>
        </div>
      </div>

      {/* BD Branding section */}
      <div className='bd-branding-home'>
        <div className='row'>
          <div className='col-md-6 bd-exchange-home-img'>
            <img src={Diamonds3} alt='img' className='img-fluid' />
          </div>

          <div className='col-md-6 bd-exchange-home-intro'>
            <h2>BD Branding</h2>
            <p className=''>
              Branding has never been more attractive. Say goodbye to poor
              branding, sloppy designs and grim advertising: it’s time to turn
              the heat up for your business. We want that for you just as much
              as you do.
            </p>

            <div className='bd-exchange-home-link'>
              <Link to='/graphics-design-branding'>Learn more </Link>
            </div>
          </div>
          <div className='col-md-6 display-on-small-screen'>
            <img src={Branding} alt='img' className='img-fluid' />
          </div>
        </div>
      </div>
      {/* </div> */}

      {/* BD Web section */}
      <div className='bd-web-home'>
        <div className='row'>
          <div className='col-md-6 bd-exchange-home-intro'>
            <h2>BD Web</h2>
            <p className=''>
              We understand the uniqueness of each brand, and will work with you
              to create a failure-proof online strategy that is fitting for your
              brand. We can bring the website you always dreamed of to life, and
              every other online dream you have had for your brand.
            </p>

            <div className='bd-exchange-home-link'>
              <Link to='/web'>Learn more </Link>
            </div>
          </div>
          <div className='col-md-6 bd-exchange-home-img'>
            <img src={Diamonds4} alt='img' className='img-fluid' />
          </div>
          <div className='col-md-6 display-on-small-screen'>
            <img src={Web} alt='img' className='img-fluid' />
          </div>
        </div>
      </div>

      {/* BD Prints section */}
      <div className='bd-prints-home'>
        <div className='row'>
          <div className='col-md-6 bd-exchange-home-img'>
            <img src={Diamonds5} alt='img' className='img-fluid' />
          </div>
          <div className='col-md-6 bd-exchange-home-intro'>
            <h2>BD Print</h2>
            <p className=''>
              High-quality and satisfying prints is something we enjoy giving.
              Beyond offline prints at our physical location, you can equally
              get your prints done from your phone.
            </p>
            <p>
              Simply pick up your phone, place your order, send your materials
              or have us create one for you, and drop your address. We do not
              know distance.
            </p>

            <div className='bd-exchange-home-link'>
              <Link to='/printing'>Learn more </Link>
            </div>
          </div>
          <div className='col-md-6 display-on-small-screen'>
            <img src={Diamonds51} alt='img' className='img-fluid' />
          </div>
        </div>
      </div>

      {/* Services */}
      <ServicesSlider />

      {/* Quote */}
      <div className='mobile-quote-wrapper'>
        <div className='download-moble-app row'>
          <div className='col-md-6 download-moble-app-img'>
            <img src={Diamonds6} alt='' />
          </div>
          <div className='col-md-6 download-moble-app-content'>
            <h2>
              Download our <br />
              Mobile App
            </h2>
            <div className='download-app-btns'>
              <img src={Diamonds7} alt='' className='img1' />
              <img src={Diamonds8} alt='' className='img2' />
            </div>
          </div>
        </div>

        <div className='quote-wrapper'>
        <h1 className='fw-bold text-center pt-5'>REQUEST QUOTE</h1>
          <div className='quote-wrapper-img'>
            <img src={Diamonds9} alt='' className='left-side img-fluid' />
            <img src={Diamonds10} alt='' className='right-side img-fluid' />
          </div>
          <div className='p-3'>
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

      {/* Testimony Slider */}
      <div className='my-5'>
        <div className='my-5'>
          <TestimonySlider />
        </div>

        {/* Partners */}
        <Partners />

        {/*  */}
        <Ecosystem />

        {/* faq */}
        <div className='faq py-5 px-3'>
          <div className='faq-hero-career-wrapper text-center'>
            <h2 className='my-5'>Frequently Asked Questions</h2>
          </div>
          <Faqs />
          <p className='text-center mt-5 text-bold'>Cant find your question?</p>
          <div className='faq-cta-btns d-flex justify-content-center align-items-top'>
            <Link to='/faq'>
              <button
                className='one'
              >
                Show me More
              </button>
            </Link>
          </div>
        </div>

        {/* Subscribe */}
        <div className='fix-home-err'>
          <Subscribe />
        </div>
      </div>
      <Footer />
    </div>
  );
};

export default Home;
