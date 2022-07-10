import React, { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import "./index.scss";
import CompanyLogo from "../../assets/logo.svg";
import HomeIcon from "../../assets/home-icon.svg";
import ServicesIcon from "../../assets/services-icon.svg";
import TransactionsIcon from "../../assets/transactions-icon.svg";
import WalletIcon from "../../assets/Vector (2).svg";
import AccountSettingsIcon from "../../assets/account-settings-icon.svg";
import LogoutIcon from "../../assets/logout-icon.svg";
import { NavLink, useLocation } from "react-router-dom";
import { RiArrowDropDownLine } from "react-icons/ri";
import { useEffect } from "react";
import { getServices } from "../../redux/actions/service";
import SideBarAppDownload from "../../assets/sidebar-app-download.svg";

const SideBar = ({ toggleSidebar, closeSidebar }) => {
  const dispatch = useDispatch();

  const servicesData = useSelector((state) => state.service);
  const service = servicesData?.service?.data;
  console.log("service", service);
  const [show, setShow] = useState(false);

  const signOut = () => {
    localStorage.clear();
    window.location.reload(false);
  };

  useEffect(() => {
    dispatch(getServices());
  }, [dispatch]);
  const location = useLocation()
  return (
    <div className='sidebar-container'>
      <div className='company-logo-container'>
        <img src={CompanyLogo} alt='Company logo' />
      </div>
      <div className='dashboard__link__wrapper'>
        <ul className=''>
          <li>
            <NavLink
              to='/app/dashboard'
              className='dashboard-link'
              // onClick={toggleSidebar}
              onClick={closeSidebar}
            >
              <img src={HomeIcon} alt='Home icon' className='' />
              <span className='link__title'>Home</span>
            </NavLink>
          </li>
          <li
            className='display__sidebar__dropdown'
          // onClick={() => setShow(!show)}
          >
            <a className='dashboard-link' onClick={() => setShow(!show)}>
              <img src={ServicesIcon} alt='Home icon' className='' />
              <div className='sidebar__services__link__container'>
                <span className='link__title'>Services</span>
                <span className='sidebar__dropdown__icon'>
                  <RiArrowDropDownLine />
                </span>
              </div>
            </a>
            {show && (
              <div className='sidebar__services__dropdown__container'>
                <ul>
                  {service &&
                    service?.map((link, id) => {
                      return(
                      <React.Fragment key={id}>
                        {
                          <li className={`'logout-li' ${location.pathname.includes(link.slug) && 'active'}`}>
                            <NavLink
                              onClick={closeSidebar}
                              to={`/app/${link.title
                                .replace(" ", "_")
                                .toLowerCase()}`}
                            >
                              <span className='logout-text'>{link.title}</span>
                            </NavLink>
                          </li>}
                      </React.Fragment>
                    )})}
                </ul>
              </div>
            )}
          </li>
          <li>
            <NavLink
              onClick={closeSidebar}
              to='/app/bd_transactions' className='dashboard-link'>
              <img src={TransactionsIcon} alt='Home icon' className='' />
              <span className='link__title'>Transactions</span>
            </NavLink>
          </li>
          <li>
            <NavLink
              onClick={closeSidebar}
              to='/app/bd_offers' className={(isActive)=>'active'+(!isActive ? 'dashboard-link': 'dashboard-link')}>
              <img src={TransactionsIcon} alt='Home icon' className='' />
              <span className='link__title'>Offers</span>
            </NavLink>
          </li>
          <li>
            <NavLink
              onClick={closeSidebar}
              to='/app/wallet' className='dashboard-link'>
              <img src={WalletIcon} alt='Home icon' className='' />
              <span className='link__title'>Wallet</span>
            </NavLink>
          </li>
          <li>
            <NavLink
              onClick={closeSidebar}
              to='/app/account_setting' className='dashboard-link'>
              <img src={AccountSettingsIcon} alt='Home icon' className='' />
              <span className='link__title'>Account Settings</span>
            </NavLink>
          </li>
        </ul>
        <ul>
          <li className='sidebar__logout__link'>
            <NavLink to='/' onClick={signOut}>
              <img src={LogoutIcon} alt='Logout icon' className='mr-5' />
              <span className='logout-text'>LOGOUT</span>
            </NavLink>
          </li>
        </ul>
      </div>

      <div className='px-3 app_download_container'>
        <img src={SideBarAppDownload} className='app_download_img' />
        {/* <button className='app_download_button'>Download App</button> */}
      </div>
    </div>
  );
};

export default SideBar;
