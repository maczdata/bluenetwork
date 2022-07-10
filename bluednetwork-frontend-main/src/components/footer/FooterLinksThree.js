import React from 'react'
import {Link} from "react-router-dom"

// css
import './Footer.css'

// Icons
import Facebook from "../../assets/Facebook.svg";
import Twitter from "../../assets/Twitter.svg";
import Instagram from "../../assets/Instagram.svg";
import messageIcon from "../../assets/message.svg";
import phoneIcon from "../../assets/phone-icon.svg";
import addressIcon from "../../assets/address-icon.svg";

const FooterLinksThree = () => {
    return (
      <div className="col-md-3 col-sm-6 col-xs-12  footer-links-three">
        <div className="footer-social">
          <h5>SOCIAL</h5>
          <ul>
            <Link to="https://facebook.com">
              <li >
                <img src={Facebook} alt="" />
              </li>
            </Link>
            <Link to="https://twitter.com">
              <li>
                <img src={Twitter} alt="" />
              </li>
            </Link>
            <Link to="https://instagram.com">
              <li>
                <img src={Instagram} alt="" />
              </li>
            </Link>
          </ul>

          <h5>CONTACT</h5>
          <ul className="footer-address">
            <Link to="gmail.com">
              <li >
                <div className="social-icon-bg mr-3"><img src={messageIcon} alt="" srcset="" /></div>
                <p>hello@bluedservices.com.ng</p>
              </li>
            </Link>
            <Link to="">
              <li className="d-flex align-items-center mt-3">
              <div className="social-icon-bg"><img src={phoneIcon} alt="" /></div>
                <p>+2348093844767</p>
              </li>
            </Link>
            <Link to="">
              <li className="d-flex align-items-center mt-3">
              <div className="social-icon-bg"> <img src={addressIcon} alt="" /></div>
                <p>
                  Km 1, Otubtraco plaza,
                  <br /> near orpet junction,
                  <br /> Ikot Ekpene Rd, Umuahia, Abia state.
                </p>
              </li>
            </Link>
          </ul>
        </div>
      </div>
    );
}

export default FooterLinksThree
