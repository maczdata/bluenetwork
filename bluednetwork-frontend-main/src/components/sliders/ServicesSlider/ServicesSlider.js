import React, { Component } from "react";
import Slider from "react-slick";
import "./ServicesSlider.css";
import Icon1 from "../../../assets/gift-card 1.svg";
import Icon2 from "../../../assets/payment-method.svg";
import Icon3 from "../../../assets/services.svg";
import Icon4 from "../../../assets/design 1 (1).svg";

export default class ServicesSlider extends Component {
  render() {
    var settings = {
      dots: true,
      infinite: false,
      speed: 500,
      slidesToShow: 4,
      slidesToScroll: 4,
      initialSlide: 0,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            dots: true,
          },
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            initialSlide: 2,
          },
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
    };
    return (
      <div className='container services-wrapper text-center'>
        <h2>Services</h2>
        <Slider {...settings}>
        <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon3} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Printing </p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon4} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Typesetting</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon4} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Television</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon2} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Airtime to Cash</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon4} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Electricity</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon3} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Data</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon4} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Social media management</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon4} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Website design</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon4} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Copywriting</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon3} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Branding Combo</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon4} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Graphics Design</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon2} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>Airtime to Cash</p>
            </div>
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon1} alt='' />
            </div>
            {/* <div className='services-cards-icon'>
              <p>Gift Cards Exchange</p>
            </div> */}
          </div>
          <div className='services-cards'>
            <div className='services-cards-icon-wrapper'>
              <img src={Icon3} alt='' />
            </div>
            <div className='services-cards-icon'>
              <p>CAC Registration</p>
            </div>
          </div>
        </Slider>
      </div>
    );
  }
}
