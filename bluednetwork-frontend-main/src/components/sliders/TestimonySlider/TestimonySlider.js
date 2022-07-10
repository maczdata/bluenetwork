import React, { Component } from "react";
import Slider from "react-slick";
import "./TestimonySlider.css";
import IMG1 from "../../../assets/Ellipse 15.svg";
import IMG2 from "../../../assets/Group 76.svg";
export default class TestimonySlider extends Component {
  render() {
    // const settings = {
    //   dots: true,
    //   className: "center",
    //   centerMode: true,
    //   infinite: true,
    //   centerPadding: "60px",
    //   slidesToShow: 3,
    //   autoplay: true,
    //   speed: 2000,
    //   autoplaySpeed: 2000,
    //   cssEase: "linear",
      
    // };

    var settings = {
      dots: true,
      className: "center",
      centerMode: true,
      infinite: true,
      centerPadding: "60px",
      slidesToShow: 3,
      autoplay: true,
      speed: 2000,
      autoplaySpeed: 2000,
      cssEase: "linear",
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            initialSlide: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    }

    return (
      <div className='testimony-slider-wrapper text-center'>
        <h2 className='my-5 text-center'>What people say about us</h2>
        <Slider {...settings}>
          <div className='testimony-cards'>
            <div className='first-part'>
              <div className='testimony-cards-img'>
                <img src={IMG2} alt='' srcset='' />
              </div>
              <div className='testimony-cards-title'>
                <h5>Customer Name</h5>
                <p>Organization</p>
              </div>
            </div>
            <div className='testimony-card-text'>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.{" "}
              </p>
            </div>
            <img src={IMG1} alt='' />
          </div>
          <div className='testimony-cards'>
            <div className='first-part'>
              <div className='testimony-cards-img'>
                <img src={IMG2} alt='' srcset='' />
              </div>
              <div className='testimony-cards-title'>
                <h5>Customer Name</h5>
                <p>Organization</p>
              </div>
            </div>
            <div className='testimony-card-text'>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.{" "}
              </p>
            </div>
            <img src={IMG1} alt='' />
          </div>
          <div className='testimony-cards'>
            <div className='first-part'>
              <div className='testimony-cards-img'>
                <img src={IMG2} alt='' srcset='' />
              </div>
              <div className='testimony-cards-title'>
                <h5>Customer Name</h5>
                <p>Organization</p>
              </div>
            </div>
            <div className='testimony-card-text'>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.{" "}
              </p>
            </div>
            <img src={IMG1} alt='' />
          </div>
          <div className='testimony-cards'>
            <div className='first-part'>
              <div className='testimony-cards-img'>
                <img src={IMG2} alt='' srcset='' />
              </div>
              <div className='testimony-cards-title'>
                <h5>Customer Name</h5>
                <p>Organization</p>
              </div>
            </div>
            <div className='testimony-card-text'>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.{" "}
              </p>
            </div>
            <img src={IMG1} alt='' />
          </div>
          <div className='testimony-cards'>
            <div className='first-part'>
              <div className='testimony-cards-img'>
                <img src={IMG2} alt='' />
              </div>
              <div className='testimony-cards-title'>
                <h5>Customer Name</h5>
                <p>Organization</p>
              </div>
            </div>
            <div className='testimony-card-text'>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.{" "}
              </p>
            </div>
            <img src={IMG1} alt='' />
          </div>
          <div className='testimony-cards'>
            <div className='first-part'>
              <div className='testimony-cards-img'>
                <img src={IMG2} alt='' srcset='' />
              </div>
              <div className='testimony-cards-title'>
                <h5>Customer Name</h5>
                <p>Organization</p>
              </div>
            </div>
            <div className='testimony-card-text'>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.{" "}
              </p>
            </div>
            <img src={IMG1} alt='' />
          </div>
        </Slider>
      </div>
    );
  }
}
