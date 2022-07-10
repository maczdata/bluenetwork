import React, { Component } from "react";
import Slider from "react-slick";
import Banner from "../../assets/Frame 1.png";

export default class Fade extends Component {
  render() {
    const settings = {
      dots: false,
      fade: true,
      infinite: true,
      speed: 1000,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      // speed: 2000,
      autoplaySpeed: 2000,
    };
    return (
      <div>
        {/* <h2>Fade</h2> */}
        <Slider {...settings}>
          <div>
            <img src={Banner} alt="banner" className="img-fluid" />
          </div>
          <div>
            <img src={Banner} alt="banner" className="img-fluid" />
          </div>
          <div>
            <img src={Banner} alt="banner" className="img-fluid" />
          </div>
          <div>
            <img src={Banner} alt="banner" className="img-fluid" />
          </div>
        </Slider>
      </div>
    );
  }
}