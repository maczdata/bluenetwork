import React from "react";
import CtaBtn from "./Cta-btns/CtaBtn";
import './CtaSection.css'

const CtaSection = ({ HeroTitle, HeroText, HeroImgSrc }) => {
  return (
    <div>
      {/* Hero section */}
      <div className="container mt-5">
        <div className="row">
          <div className="col-md-6 Cta-Section">
            <h1>{HeroTitle}</h1>
            <p className="hero-text">{HeroText}</p>
            <CtaBtn />
          </div>
          <div className="col-md-6 hero-img">
            <img src={HeroImgSrc} alt="img" className="img-fluid" />
          </div>
        </div>
      </div>
    </div>
  );
};

export default CtaSection;
