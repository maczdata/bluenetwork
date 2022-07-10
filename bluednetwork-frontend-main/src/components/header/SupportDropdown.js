import React from "react";
import { Link } from "react-router-dom";

const SupportDropdown = ({ handleCloseHamburger }) => {
  return (
    <div className='support-dropdown'>
      <ul>
        <li>
          <Link to='/faq' onClick={handleCloseHamburger}>
            Frequently Asked Questions
          </Link>
        </li>
        <li>
          <Link to='/contact' onClick={handleCloseHamburger}>
            Contact Us
          </Link>
        </li>
      </ul>
    </div>
  );
};

export default SupportDropdown;
