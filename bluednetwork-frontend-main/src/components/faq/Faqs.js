import React from "react";
import Faq from "react-faq-component";
import { Link } from "react-router-dom";
import "./Faqs.scss";

const data = {
  // title: "FAQ (How it works)",
  rows: [
    {
      title: "How can i qualify for the N10 free deposit on every login?",
      content:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ",
    },
    {
      title: "How can i qualify for the N10 free deposit on every login?",
      content:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ",
    },
    {
      title: "How can i qualify for the N10 free deposit on every login?",
      content:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ",
    },
    {
      title: "How can i qualify for the N10 free deposit on every login?",
      content:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ",
    },
    {
      title: "How can i qualify for the N10 free deposit on every login?",
      content:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ",
    },
  ],
};

const Faqs = () => {
  return (
    <div className='faq-home-container'>
      <Faq
        data={data}
        styles={{
          bgColor: "#ffffff",
          titleTextColor: "#48482a",
          // rowTitleColor: "#78789a",
          rowTitleTextSize: "large",
          rowContentColor: "black",
          rowContentTextSize: "16px",
          rowContentPaddingTop: "20px",
          rowContentPaddingBottom: "20px",
          // rowContentPaddingLeft: "50px",
          // rowContentPaddingRight: "150px",
          arrowColor: "black",
          boxShadow: "4px 0px 10px rgba(2, 2, 2, 0.07)",
        }}
      />
      {/* <p className='text-center mt-5 text-bold'>Cant find your question?</p>
      <div className='faq-cta-btns d-flex justify-content-center align-items-top'>
        <Link to='/faq'>
          <button
            className='one'
            // onClick={() => setShowCollection(true)}
          >
            Show me More
          </button>
        </Link>
      </div> */}
    </div>
  );
};

export default Faqs;
