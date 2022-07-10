import React, { useState } from "react";
import { useSelector } from "react-redux";
import "./header.scss";
import Logo from "../../assets/logo.svg";
import Search from "../../assets/search.svg";
import { Link } from "react-router-dom";
import { ImCross } from "react-icons/im";
import { GiHamburgerMenu } from "react-icons/gi";
import { RiArrowDropDownLine } from "react-icons/ri";
import ServicesDropdown from "./ServicesDropdown";
import CompanyDropdown from "./CompanyDropdown";
import SupportDropdown from "./SupportDropdown";
import useGetServices from "../../hooks/useGetServices";

const Header = () => {
  const auth = useSelector((state) => state.auth);
  const { isAuthenticated } = auth;

  const signOut = () => {
    localStorage.clear();
    window.location.reload(false);
    // console.log("working");
  };

  const [navLinkOpen, navLinkToggle] = useState(false);

  // Toggle hamburger button
  const handleNavLinksToggle = () => {
    navLinkToggle(!navLinkOpen);
  };

  // add hamburger active class to container classname
  const handleNavClass = () => {
    let classes = "links-wrapper";
    if (navLinkOpen) {
      classes += " hamburger-active";
    }
    return classes;
  };

  const handleCloseHamburger = () => {
    navLinkToggle(false);
  };

  return (
    <div className='container'>
      <div className='header-wrapper'>
        <div className='header-top'>
          <ul>
            <li>
              <Link to='/'>
                <img src={Logo} alt='logo' />
              </Link>
            </li>
          </ul>
        </div>
        <div className='hamburger-icon'>
          <GiHamburgerMenu
            className='nav-icon'
            onClick={handleNavLinksToggle}
          />
        </div>
        <div className={handleNavClass()}>
          <ImCross className='nav-icon-cancel' onClick={handleCloseHamburger} />

          <div className='nav-links'>
            <ul>
              <li>
                <Link to='/' onClick={handleCloseHamburger}>
                  Home
                </Link>
              </li>
              <li className='services__dropdown__wrapper'>
                <Link>
                  Services{" "}
                  <span>
                    <RiArrowDropDownLine />
                  </span>
                </Link>
                <ServicesDropdown handleCloseHamburger={handleCloseHamburger} />
              </li>
              <li className='company__dropdown__wrapper'>
                <Link>
                  Company{" "}
                  <span>
                    <RiArrowDropDownLine />
                  </span>
                </Link>
                <CompanyDropdown handleCloseHamburger={handleCloseHamburger} />
              </li>
              <li>
                <Link to='/blogs' onClick={handleCloseHamburger}>
                  Blog
                </Link>
              </li>
              <li className='support__dropdown__wrapper'>
                <Link>
                  Support{" "}
                  <span>
                    <RiArrowDropDownLine />
                  </span>
                </Link>
                <SupportDropdown handleCloseHamburger={handleCloseHamburger} />
              </li>
            </ul>
          </div>
          <div className='auth-links' onClick={handleCloseHamburger}>
            <ul>
              {isAuthenticated ? (
                <li className='signup-link'>
                  <Link to='/app/dashboard'>
                    Dashboard
                  </Link>
                </li>
              ) : (
                <>
                  <li className='login-link'>
                    <Link to='/login'>Login</Link>
                  </li>
                  <li className='signup-link'>
                    <Link to='/sign-up'>SignUp</Link>
                  </li>
                  <li className='search-link'>
                    <img src={Search} alt='search bar' />
                  </li>
                </>
              )}
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Header;
