import React from "react";
import "./index.css";
import HeroPrintingImg from "../../assets/printing-hero-img.svg";
import BannersImg from "../../assets/banners-img.svg";
import FliersImg from "../../assets/fliers-img.svg";
import CardsImg from "../../assets/cards-img.svg";
import DocumentsImg from "../../assets/documents-img.svg";
import TypeSettingImg from "../../assets/typesetting-img.svg";
import Diamonds9 from "../../assets/Diamonds (1).svg";
import Diamonds10 from "../../assets/Diamonds (2).svg";
import QuoteForm from "../../components/form/QuoteForm";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";
import Data from "../../assets/changes/p1.png";
import TV from "../../assets/changes/p2.png";

const index = () => {
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
  const printingServicesCards = [
    {
      img: BannersImg,
      text: "Banners",
    },
    {
      img: FliersImg,
      text: "Fliers",
    },
    {
      img: CardsImg,
      text: "Cards",
    },
    {
      img: DocumentsImg,
      text: "Documents",
    },
    {
      img: TypeSettingImg,
      text: "Typesetting",
    },
  ];
  return (
    <>
      <Header />
      <div>
        {/* Hero section */}
        <div className=' mt-5'>
          <div className='container'>
            <div className='row fix-spacing'>
              <div className='col-md-6 hero-home-intro add_pd'>
                <h1>BD Print</h1>
                <p className=''>
                  We print documents and materials with ease, regardless of your
                  location. You upload your materials or have us create one for
                  you, we get them printed and delivered to you.
                </p>
                <CtaBtn />
              </div>
              <div className='col-md-6 hero-home-img'>
                <img src={HeroPrintingImg} alt='img' className='img-fluid' />
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
                <h1>Printing</h1>
                <p className=''>
                  Say goodbye to poor branding, sloppy designs and grim
                  advertising; it’s time to turn the heat up for your business.
                  We want that for you just as much as you do.
                </p>

                <button className='one-btn my-4'>Proceed</button>
              </div>
            </div>

            <div className='row fix-spacing'>
              <div className='col-md-6 display-on-small-screen'>
                <img src={Data} alt='img' className='img-fluid' />
              </div>
              <div className='col-md-6 hero-home-intro add_pd'>
                <h1>Typesetting</h1>
                <p className=''>
                  Say goodbye to poor branding, sloppy designs and grim
                  advertising; it’s time to turn the heat up for your business.
                  We want that for you just as much as you do.
                </p>

                <button className='one-btn my-4'>Proceed</button>
              </div>
              <div className='col-md-6 hide-on-small-screen'>
                <img src={Data} alt='img' className='img-fluid' />
              </div>
            </div>
          </div>
        </div>
        {/* Printing Services Section */}
        <div className='printing-services-and-quote'>
          {/* Services */}
          {/* <div className='mx-auto pt-5 printing-services-container'>
            <div className=' printing-wrapper'>
              
              {printingServicesCards.map((card) => (
                <div className='d-flex flex-column text-center justify-content-center align-items-center printing-services-card mx-auto'>
                  <img src={card.img} alt='' className='mt-3' />
                  <p className='mt-5 secondary-blue'>{card.text}</p>
                </div>
              ))}
            </div>
          </div> */}
          {/* Get Quote */}
          <div className='printing-quote-wrapper'>
          <h1 className='fw-bold text-center pt-5'>REQUEST QUOTE</h1>
            <div className='printing-quote-wrapper-img'>
              <img src={Diamonds9} alt='' className='left-side img-fluid' />
              <img src={Diamonds10} alt='' className='right-side img-fluid' />
            </div>
            <div className='printing-quote-form'>
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
      </div>
      {/* Empowered Section */}
      <Ecosystem />

      <Footer />
    </>
  );
};

export default index;
