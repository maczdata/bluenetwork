import React from "react";
import cultureIcon from "../../assets/culture-icon.svg";
import cardBottomShape from "../../assets/culture-curve.svg";
import Group33 from "../../assets/Group 33.svg";
import "./OurCulture.css";

const cultureCards = [
  { item: "Positivity" },
  { item: "Proactivity" },
  { item: "Productivity" },
  { item: "Purpose" },
  { item: "Passion" },
];

const OurCulture = () => {
  return (
    <div className="culture-container">
      <div className="left-svg">
        <img src={Group33} alt="" />
      </div>
      <div className="culture-contents">
        <div className="culture-header text-center">
          {/* Icon */}
          <img className="culture-icon" src={cultureIcon} alt="" />
          <h3 className="culture-heading">Our Culture</h3>
        </div>
        <div className="cards-wrap">
          <div className="cards-row">
            {/* Cards */}
            {cultureCards.map((card) => (
              <div className="culture-card">
                <p>{card.item}</p>
                <div className="bottom-svg">
                  <img src={cardBottomShape} alt="" />
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default OurCulture;
