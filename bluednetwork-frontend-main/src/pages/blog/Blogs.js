import React from "react";
import "./Blogs.css";
import IMG1 from "../../assets/happy-joyful-corporate-coach-holding-pen 11.svg";
import { Link } from "react-router-dom";
import IMG2 from "../../assets/Group 83.svg";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";

const Blogs = () => {
  return (
    <>
      <Header />
      <div className='blogs-wrapper-container'>
        <div className='private-policy-title'>
          <h2>Blog</h2>
        </div>
        <div className='blogs-wrapper'>
          <div className='single-blog'>
            <img src={IMG1} alt='' />
            <div className='single-blog-content'>
              <h2>
                HOW GOOD DIGITAL MARKETING CAN
                <br /> INCREASE YOUR ROI BY OVER 200%
              </h2>
              <div className='single-blog-content-status'>
                <div className='single-blog-content-status-img'>
                  <img src={IMG2} alt='' />
                </div>
                <div className='single-blog-status-content'>
                  <p>Blue-D Admin</p>
                  <div className='single-blog-status-content-time'>
                    <ul>
                      <li className='coloured-time'>3 days ago</li>
                      <li>5 mins read</li>
                    </ul>
                  </div>
                </div>
              </div>
              <p className='mt-2'>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusm...
              </p>
              <button>
              <Link to='/blog/1'>Learn more</Link>
              </button>
            </div>
          </div>
          <div className='single-blog'>
            <img src={IMG1} alt='' />
            <div className='single-blog-content'>
              <h2>
                HOW GOOD DIGITAL MARKETING CAN
                <br /> INCREASE YOUR ROI BY OVER 200%
              </h2>
              <div className='single-blog-content-status'>
                <div className='single-blog-content-status-img'>
                  <img src={IMG2} alt='' />
                </div>
                <div className='single-blog-status-content'>
                  <p>Blue-D Admin</p>
                  <div className='single-blog-status-content-time'>
                    <ul>
                      <li className='coloured-time'>3 days ago</li>
                      <li>5 mins read</li>
                    </ul>
                  </div>
                </div>
              </div>
              <p className='mt-2'>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusm...
              </p>
              <button>
              <Link to='/blog/1'>Learn more</Link>
              </button>
            </div>
          </div>
          <div className='single-blog'>
            <img src={IMG1} alt='' />
            <div className='single-blog-content'>
              <h2>
                HOW GOOD DIGITAL MARKETING CAN
                <br /> INCREASE YOUR ROI BY OVER 200%
              </h2>
              <div className='single-blog-content-status'>
                <div className='single-blog-content-status-img'>
                  <img src={IMG2} alt='' />
                </div>
                <div className='single-blog-status-content'>
                  <p>Blue-D Admin</p>
                  <div className='single-blog-status-content-time'>
                    <ul>
                      <li className='coloured-time'>3 days ago</li>
                      <li>5 mins read</li>
                    </ul>
                  </div>
                </div>
              </div>
              <p className='mt-2'>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusm...
              </p>
              <button>
              <Link to='/blog/1'>Learn more</Link>
              </button>
            </div>
          </div>
          <div className='single-blog'>
            <img src={IMG1} alt='' />
            <div className='single-blog-content'>
              <h2>
                HOW GOOD DIGITAL MARKETING CAN
                <br /> INCREASE YOUR ROI BY OVER 200%
              </h2>
              <div className='single-blog-content-status'>
                <div className='single-blog-content-status-img'>
                  <img src={IMG2} alt='' />
                </div>
                <div className='single-blog-status-content'>
                  <p>Blue-D Admin</p>
                  <div className='single-blog-status-content-time'>
                    <ul>
                      <li className='coloured-time'>3 days ago</li>
                      <li>5 mins read</li>
                    </ul>
                  </div>
                </div>
              </div>
              <p className='mt-2'>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusm...
              </p>
              <button>
              <Link to='/blog/1'>Learn more</Link>
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Empowered Section */}
      <Ecosystem />

      <Footer />
    </>
  );
};

export default Blogs;
