import React from "react";
import "./Contact.css";
import {Link} from "react-router-dom"

// components
import FooterLinksThree from "../../components/footer/FooterLinksThree";

// Imgs
import contactLeftSvg from "../../assets/contact-left.svg";
import contactRightSvg from "../../assets/contact-right.svg";
import contactUsSvg from "../../assets/contact-us.svg";
import contactMapSvg from "../../assets/contact-map.svg";
import QuoteForm from "../../components/form/QuoteForm";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";

import Facebook from "../../assets/Facebook.svg";
import Twitter from "../../assets/Twitter.svg";
import Instagram from "../../assets/Instagram.svg";
import messageIcon from "../../assets/message.svg";
import phoneIcon from "../../assets/phone-icon.svg";
import addressIcon from "../../assets/address-icon.svg";

const contactBtnStyle = {
  background: "#0003B8",
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
  margin: "15px auto",
};
const Contact = () => {
  return (
    <>
      <Header />
      <div className='contact-page_wrapper'>
        {/* Left svg */}
        {/* <div className='contact_left-svg'>
          <img className='img-fluid' src={contactLeftSvg} alt='' />
        </div> */}
        <div className='contact-page_content'>
          {/* contact-us svg */}
          <div className='contact-us_svg'>
            <img className='contact-us_img' src={contactUsSvg} alt='' />
          </div>

          {/* contact-form-area */}
          <div className='contact-form_area'>
            {/* contact-form */}
            <div className='contact-form'>
              <QuoteForm
                ctaBtnText={"Send Message"}
                ctaBtnStyle={contactBtnStyle}
                formTitle={"Contact Us"}
              />
            </div>

            {/* contact-handles and social-media */}
            <div className='contact-social_handles mt-5'>
              <div className='col-md-3 col-sm-6 col-xs-12  footer-links-three'>
                <div className='footer-social'>
                  <h5>SOCIAL</h5>
                  <ul>
                    <Link to='https://facebook.com'>
                      <li>
                        <img src={Facebook} alt='' />
                      </li>
                    </Link>
                    <Link to='https://twitter.com'>
                      <li>
                        <img src={Twitter} alt='' />
                      </li>
                    </Link>
                    <Link to='https://instagram.com'>
                      <li>
                        <img src={Instagram} alt='' />
                      </li>
                    </Link>
                  </ul>

                  <h5>CONTACT</h5>
                  <ul className='footer-address'>
                    {/* <Link to='#'> */}
                      <li>
                        <div className='social-icon-bg mr-3'>
                          <img src={messageIcon} alt='' srcset='' />
                        </div>
                        <p>hello@bluedservices.com.ng</p>
                      </li>
                    {/* </Link> */}
                    {/* <Link to=''> */}
                      <li className='d-flex align-items-center mt-3'>
                        <div className='social-icon-bg'>
                          <img src={phoneIcon} alt='' />
                        </div>
                        <p>+2348093844767</p>
                      </li>
                    {/* </Link> */}
                    {/* <Link to=''> */}
                      <li className='d-flex align-items-center mt-3'>
                        <div className='social-icon-bg'>
                          {" "}
                          <img src={addressIcon} alt='' />
                        </div>
                        <p>
                          Km 1, Otubtraco plaza,
                          <br /> near orpet junction,
                          <br /> Ikot Ekpene Rd, Umuahia, Abia state.
                        </p>
                      </li>
                    {/* </Link> */}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* map area */}
        <div className='contact-map'>
          <img className='contact_map-svg' src={contactMapSvg} alt='' />
        </div>

        {/* Empowered Section */}
        <Ecosystem />

        {/* Right svg */}
        <div className='contact_right-svg'>
          <img className='img-fluid' src={contactRightSvg} alt='' />{" "}
        </div>
      </div>
      <Footer />
    </>
  );
};

export default Contact;
