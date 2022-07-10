import React from "react";
import "./Cta.css";
// import Diamonds7 from "../../../assets/Google Play Badge (1).svg";
// import Diamonds8 from "../../../assets/Apple Play Badge (1).svg";
import Diamonds7 from "../../../assets/changes/Google Play Badge.png";
import Diamonds8 from "../../../assets/changes/Apple Play Badge.png";

const CtaBtn = () => {
  return (
    <div className='cta-btn'>
      <div className='download-app-btns'>
        <img src={Diamonds7} alt='' className='img1' />
        <img src={Diamonds8} alt='' className='img2' />
      </div>
      <button className='getStartedBtn'>Get Started</button>
    </div>
  );
};

export default CtaBtn;
