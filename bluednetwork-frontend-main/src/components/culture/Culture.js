import React from "react";
import "./Culture.css";
import IMG1 from "../../assets/Ellipse 15.svg";

const Culture = () => {
  return (
    <div className='culture-wrapper'>
      <div className='culture-cards'>
        <div className='culture-card-content'>Positivity</div>

        <img src={IMG1} alt='' />
      </div>
      <div className='culture-cards'>
        <div className='culture-card-content'>Proactivity</div>

        <img src={IMG1} alt='' />
      </div>
      <div className='culture-cards'>
        <div className='culture-card-content'>Positivity</div>

        <img src={IMG1} alt='' />
      </div>
      <div className='culture-cards'>
        <div className='culture-card-content'>Purpose</div>

        <img src={IMG1} alt='' />
      </div>
      <div className='culture-cards'>
        <div className='culture-card-content'>Passion</div>

        <img src={IMG1} alt='' />
      </div>
    </div>
  );
};

export default Culture;
