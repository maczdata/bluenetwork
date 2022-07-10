import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import useGetServices from "../../hooks/useGetServices";
import { getServices } from "../../redux/actions/service";

const ServicesDropdown = ({ handleCloseHamburger }) => {
  const dispatch = useDispatch()
  const servicesData = useSelector((state) => state.service);
  const service = servicesData?.service?.data;
  useEffect(() => {
    dispatch(getServices());
  }, [dispatch]);
  console.log('service me', service);
  return (
    <div className='services-dropdown'>
      {service?
        (
        <ul>
          {service.map((ServiceResponseItem, idx) => {
            return <li className="sub-dropdown-wrapper">
              <Link to={`/${ServiceResponseItem.slug}`} onClick={handleCloseHamburger}>
                {ServiceResponseItem.title}
              </Link>
              <div className="sub-dropdown">
                <ul>
                  {ServiceResponseItem?.services.map((ServiceResponseItemService, idx)=>{
                    if(ServiceResponseItemService.title !== 'Gift Card Exchange')return<li><Link to="#">{ServiceResponseItemService.title}</Link></li>
                  }
                  )}
                  
                </ul>
              </div>
            </li>
          })}
        </ul>
        )
      :
      (<ul>
        <li className="sub-dropdown-wrapper">
          <Link to='/bill' onClick={handleCloseHamburger}>
            BD Bills
          </Link>
          <div className="sub-dropdown">
            <ul>
              <li><Link to="#">Airtime</Link></li>
              <li><Link to="#">Data</Link></li>
              <li><Link to="#">Television</Link></li>
              <li><Link to="#">Electricity</Link></li>
            </ul>
          </div>
        </li>
        <li className="sub-dropdown-wrapper">
          <Link to='/exchange' onClick={handleCloseHamburger}>
          BD Exchange 
          </Link>
          <div className="sub-dropdown">
            <ul>
              <li><Link to="#">Airtime to cash</Link></li>
              {/* <li><Link to="#">Giftcard</Link></li> */}
            </ul>
          </div>
        </li>
        <li className="sub-dropdown-wrapper">
          <Link to='/graphics-design-branding' onClick={handleCloseHamburger}>
          BD Branding
          </Link>
          <div className="sub-dropdown">
            <ul>
              <li><Link to="#">Graphics Design</Link></li>
              <li><Link to="#">CAC Registration</Link></li>
              <li><Link to="#">Branding Combo</Link></li>
            </ul>
          </div>
        </li>
        <li className="sub-dropdown-wrapper">
          <Link to='/web' onClick={handleCloseHamburger}>
          BD Web
          </Link>
          <div className="sub-dropdown">
            <ul>
              <li><Link to="#">Website design</Link></li>
              <li><Link to="#">Social media management</Link></li>
              <li><Link to="#">Copywriting</Link></li>
            </ul>
          </div>
        </li>
        <li className="sub-dropdown-wrapper">
          <Link to='/printing' onClick={handleCloseHamburger}>
           BD Print
          </Link>
          <div className="sub-dropdown">
            <ul>
              <li><Link to="#">Printing</Link></li>
              <li><Link to="#">Typesetting</Link></li>
            </ul>
          </div>
        </li>
        
      </ul>)}
    </div>
  );
};

export default ServicesDropdown;
