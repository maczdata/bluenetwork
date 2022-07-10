import React from "react";
import "./Subscribe.css";
import IMG1 from "../../assets/Vector (1).svg";

const Subscribe = () => {
  return (
    <div className='subscribe-wrapper'>
      <h2>Subscribe to Our Newsletters</h2>
      <p>
        Don’t be left out! Subscribe to our newsletters to stay
        <br /> up-to-date with the latest information.
      </p>
      <div className='subscribe-input'>
        <span className='icon'>
          <img src={IMG1} alt='' />
        </span>

        <input type='text' />
        <span className='btn-subscribe'>
          <button>Subscribe</button>
        </span>
      </div>
    </div>
  );
};

export default Subscribe;
