import React, { useEffect, useState } from "react";
import "./index.css";
import SearchIcon from "../../assets/dashboard-navbar-search-icon.svg";
import { GiHamburgerMenu } from "react-icons/gi";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../redux/actions/auth";
// import NotificationIcon from "../../assets/dashboard-navbar-notification-icon.svg";
// import ShoppingCartIcon from "../../assets/shopping-cart.svg";
// import ProfilePicture from "../../assets/profile-picture.svg";

const DashboardNavBar = ({ toggleSidebar }) => {
  //   const [searchInput, setsearchInput] = useState("");

  // const submitSearchInput = (e) => {
  //     e.preventDefault();
  //     console.log("this is the input", searchInput);
  // }

  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const { data } = auth;

  useEffect(() => {
    dispatch(loadUser());
  }, []);

  return (
    <div className=' d-flex  justify-content-between dashboard-navbar-container'>
      <div className='dashboard-navbar-left-container'>
        <ul>
          <li>
            <GiHamburgerMenu
              className='mobie__nav__menu'
              onClick={toggleSidebar}
            />
          </li>
          <li>
            <h5 className=' fw-bold'>HOME</h5>
          </li>
          {/* <li className='dashboard__search__icon ml-5'>
            <img src={SearchIcon} alt='Search icon' />
          </li> */}
        </ul>
        {/* <div className=''>
          <GiHamburgerMenu
            className='mobie__nav__menu'
            onClick={toggleSidebar}
          />
          <h5 className=' fw-bold'>HOME</h5>
        </div> */}

        {/* <div className='dashboard__search__icon ml-5'>
          <img src={SearchIcon} alt='Search icon' />
          <form>
                        <input type="text" placeholder="" onChange={(e) => setsearchInput(e.target.value)}/>
                        <button type="submit" onClick={(e) => submitSearchInput()}></button>
                    </form>
        </div> */}
      </div>
      {/* <div className="d-flex align-items-center dashboard-navbar-right-container ">
               <div className="d-flex justify-content-between navbar-icons-container">
                   <div className=" d-flex shopping-cart-container">
                    <img src={ShoppingCartIcon} alt="Shopping cart icon"/>
                    <div className="number-of-items text-center text-white">2</div>
                   </div>
                   <div className="notification-container">
                     <img src={NotificationIcon} alt="Notification Icon" />
                   </div>
               </div>
               <div className="d-flex align-items-center  mx-auto profile-container">
                   <img src={ProfilePicture} alt="User profile picture" className=" mr-2 user-profile"/>
                   <div>
                       <p>Wallet Balance</p>
                       <p>12,000</p>
                   </div>
               </div>
           </div> */}
      <div className='navbar__wallet__balance'>
        <h5 className=' fw-bold'>{data?.formatted_wallet_balance}</h5>
      </div>
    </div>
  );
};

export default DashboardNavBar;
