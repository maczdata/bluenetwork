import React, { Component } from "react";
import Slider from "react-slick";

export default class FormSideSlider extends Component {
  render() {
    const settings = {
      dots: true,
      infinite: true,
      speed: 500,
      slidesToShow: 1,
      slidesToScroll: 1,
    };
    return (
      <div className='text-center'>
        <Slider {...settings}>
          <div>
            <h1>Web Development</h1>
            <p>
              A vast network of digital services, right at your finger tips.
            </p>
          </div>
          <div>
            <h1>Web Development</h1>
            <p>
              A vast network of digital services, right at your finger tips.
            </p>
          </div>
          <div>
            <h1>Web Development</h1>
            <p>
              A vast network of digital services, right at your finger tips.
            </p>
          </div>
          <div>
            <h1>Web Development</h1>
            <p>
              A vast network of digital services, right at your finger tips.
            </p>
          </div>
          <div>
            <h1>Web Development</h1>
            <p>
              A vast network of digital services, right at your finger tips.
            </p>
          </div>
          <div>
            <h1>Web Development</h1>
            <p>
              A vast network of digital services, right at your finger tips.
            </p>
          </div>
        </Slider>
      </div>
    );
  }
}
