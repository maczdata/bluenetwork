import React from "react";
import { Link } from "react-router-dom";
import "./Footer.css";
import FooterImg from "../../assets/footer-logo.svg";
import FooterLinksThree from "./FooterLinksThree";

// import about from "../../pages/about/index";

const Footer = () => {
  return (
    <div className='footer-wrapper'>
      <div className='container'>
        <div className='col-md-3 col-sm-6 col-xs-12  footer-about'>
          <img src={FooterImg} alt='footer-logo' className='mb-4' />
          <p>Enjoy digital services at your fingertips. </p>
          <p>
            With only a few taps on your phone, you get access to a variety of
            services like airtime purchases, airtime to cash, data
            subscriptions
            {/* , gift cards exchanges, */}
             electricity payments, prints,
            graphic designs, CAC registration and web design.
          </p>
        </div>
        <div className='col-md-3 col-sm-6 col-xs-12  footer-links-one mt-1'>
          <div className='footer-links-one-wrapper'>
            <ul>
              <li>COMPANY</li>
              <li>
                <Link to='/about-us'>About Us</Link>
              </li>
              <li>
                <Link to='/career'>Career</Link>
              </li>
              <li>
                <Link to='/private-policy'>Privacy Policy</Link>
              </li>
              <li>Terms of Services</li>
              <li>FAQ</li>
              <li className='d-flex align-items-center mt-5 footer__auth__links'>
                <div>Login</div>
                <div className='foot-sigup'>Sign Up</div>
              </li>
            </ul>
          </div>
        </div>
        <div className='col-md-3 col-sm-6 col-xs-12 footer-links-two mt-1'>
          <div className='footer-links-one-wrapper'>
            <ul>
              <li>OUR PRODUCTS & SERVICES</li>
              <li>Web Development</li>
              <li>Data Services</li>
              <li>Airtime To Cash</li>
              {/* <li>Gift Cards Exchange</li> */}
              <li>Buy Airtime</li>
              <li>Cable Subscription</li>
              <li>Electricity Bill Payment</li>
              <li>CAC Registration</li>
            </ul>
          </div>
        </div>
        <FooterLinksThree />
      </div>
    </div>
  );
};

export default Footer;
