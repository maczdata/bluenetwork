import React from "react";
import CtaBtn from "../CallToAction/Cta-btns/CtaBtn";
import "./Ecosystem.scss";

const Ecosystem = () => {
  return (
    <div className='empowered bg-utility-left text-center'>
      <div className='container m p'>
        <div className='col-md-8 hero-home-intro'>
          <h1 className='text-center mb-4'>
            We are Building a Digital Ecosystem that empowers individuals &
            businesses in Nigeria
          </h1>
          <p className='col-md-8 mx-auto text-center'>
            We are empowering Nigerians digitally by providing a nexus of
            digital services that help meet the needs of businesses and
            individuals.
          </p>
          <div className='empowered-btn'>
            <CtaBtn />
          </div>
        </div>
      </div>
    </div>
  );
};

export default Ecosystem;
