import React from "react";
import "./Blogs.css";
import IMG1 from "../../assets/happy-joyful-corporate-coach-holding-pen 11.svg";
import IMG2 from "../../assets/Group 83.svg";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import { AiOutlineLike } from "react-icons/ai";
import { FaRegCommentAlt, FaShare } from "react-icons/fa";
import { RiShareForwardLine } from "react-icons/ri";

const Blog = () => {
  return (
    <>
      <Header />
      <div className='blogs-wrapper-container'>
        <div className='private-policy-title'>
          <h2>Blog</h2>
        </div>
        <div className='blog-wrapper'>
          <div className='d-flex  '>
            Blogs <div className='mx-2'>></div> <span>Categories</span>{" "}
            <div className='mx-2'>></div> <span>Good Digital Marketing</span>
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
                eiusm Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              </p>
              <p className='mt-2'>
                Sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmLorem ipsum dolor sit amet, consectetur adipiscing
                elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusm Lorem ipsum dolor sit amet, consectetur adipiscing
                elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusm
              </p>
              <p className='mt-2'>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusm Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              </p>
              <p className='mt-2'>
                Sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmLorem ipsum dolor sit amet, consectetur adipiscing
                elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusm Lorem ipsum dolor sit amet, consectetur adipiscing
                elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusm
              </p>
              <p className='mt-2'>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusm Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              </p>
            </div>
            <div className='socials'>
              <div className='likes'>
                <AiOutlineLike />
                <span>like</span>
              </div>
              <div className='comments'>
                <FaRegCommentAlt />
                <span>comment</span>
              </div>

              {/* <FaShare /> */}
              <div className='shares'>
                <RiShareForwardLine />
                <span>share</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

export default Blog;
