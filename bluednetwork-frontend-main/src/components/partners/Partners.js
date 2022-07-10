import React from "react";
import './partners.scss'
import AEDClogo from "../../assets/Abuja-Electricity-Distribution-Company.svg";
import PHCN from "../../assets/phed-disco.svg";
import Ninemobile from "../../assets/9mobile-logo.svg";
import Airtelogo from "../../assets/airtellogo.svg";
import DSTV from "../../assets/dstv-logo.svg";
import EEDC from "../../assets/EEDC-EnuguDisco.svg";
import Eko from "../../assets/Eko-Electricity-Distribution-Company-EKEDC.svg";
import Glo from "../../assets/glo-logo.svg";
import Gotv from "../../assets/gotv-logo.svg";
import IBEDC from "../../assets/IBEDClogo.svg";
import Ikeja from "../../assets/Ikeja-Electriclogo.svg";
import JED from "../../assets/Jed-Logo.svg";
import MTN from "../../assets/mtn-logo.svg";
import PHED from "../../assets/phed-disco.svg";

const Partners = () => {
  return (
    <div className='container partner_wrapper text-center mx-auto my-5'>
        <h2 className='mb-5'>Partners</h2>
        <div className='rows'>
          <div className='img-logo'>
            <img src={AEDClogo} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={PHCN} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={Ninemobile} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={Airtelogo} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={DSTV} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={EEDC} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={Eko} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={Glo} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={Gotv} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={IBEDC} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={Ikeja} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={JED} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={MTN} alt='img' className='img-fluid' />
          </div>
          <div className='img-logo'>
            <img src={PHED} alt='img' className='img-fluid' />
          </div>
        </div>
    </div>
  );
};

export default Partners;
