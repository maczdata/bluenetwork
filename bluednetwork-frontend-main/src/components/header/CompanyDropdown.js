import React from "react";
import { Link } from "react-router-dom";

const CompanyDropdown = ({ handleCloseHamburger }) => {
  return (
    <div className='company-dropdown'>
      <ul>
        <li>
          <Link to='/career' onClick={handleCloseHamburger}>
            Career
          </Link>
        </li>
        <li>
          <Link to='/private-policy' onClick={handleCloseHamburger}>
            Privacy Policy
          </Link>
        </li>
        <li>
          <Link to='/terms-and-condition' onClick={handleCloseHamburger}>
            Terms & Conditions
          </Link>
        </li>
      </ul>
    </div>
  );
};

export default CompanyDropdown;
