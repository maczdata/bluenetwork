import React from "react";
import Subscribe from "../../components/subscribe/Subscribe";
import "./Career.css";

// Imgs
import HeroImg from "../../assets/career-svg1.svg";
import BenefitsImg from "../../assets/hero-home-img.svg";
import CareerBanner from "../../assets/bannerImg.svg";
import AvailableJobs from "../../components/Jobs/AvailableJobs";

import data from "../../components/Jobs/data.json";
import OurCulture from "../../components/OurCulture/OurCulture";
import NoJobs from "../../components/Jobs/NoJobs/NoJobs";
import Search from "../../components/search";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";

const Career = () => {
  const [jobs] = React.useState(data.jobs);

  return (
    <>
      <Header />
      <div className='career-wrapper'>
        <h2>Career</h2>
        <p>Got something to offer? Join our team</p>
        {/* <Search /> */}
      </div>

      <div className='container mt-5 career-hero-section px-2'>
        <div className='row'>
          <div className='col-md-6 hero-home-intro'>
            <h1>Join us empower business and individuals digitally</h1>
            <p className=''>
              It would be a pleasure to have others who are passionate about
              digital services work with us. Does that sound like you? Then this
              may be right company for you.
            </p>
            {/* <CtaBtn /> */}
            <button className='one-btn my-4'>View Listings</button>
            
          </div>
          <div className='col-md-6 hero-home-img'>
            <img src={HeroImg} alt='img' className='img-fluid' />
          </div>
        </div>
      </div>

      <OurCulture />

      {/* Banner */}
      <div className='career_banner'>
        <img src={CareerBanner} alt='img' className='career_banner-img' />
      </div>

      {/* Benefits */}
      <div className='container career-hero-section-second'>
        <h2 className='text-center'>Benefits</h2>
        <p className='text-center'>
          We believe that anything is possible, and the life of Nigerians can be
          easier,
          <br /> and we are working hard towards creating these digital
          possibilities.
        </p>

        <div className='benefits'>
          <div className='each_benefit'>
            <h3>Free lunch</h3>
            <p>
              Every team member is entitled to free lunch every working day to
              keep them active.
            </p>
          </div>
          <div className='each_benefit'>
            <h3>Gym membership</h3>
            <p>Free access to a nearby gym to keep you fit and agile.</p>
          </div>
          <div className='each_benefit'>
            <h3>Professional development</h3>
            <p>
              Working with Blue-D Services helps you build a premium career
              portfolio towards achieving your professional goals.
            </p>
          </div>
          <div className='each_benefit'>
            <h3>Trustfund pension</h3>
            <p>
              A retirement savings account will be created for each team member
              of Blue-D Services.
            </p>
          </div>
          <div className='each_benefit'>
            <h3>Friendly colleagues</h3>
            <p>
              You have the opportunity to work alongside colleagues that you can
              grow with and learn from.
            </p>
          </div>
          <div className='each_benefit'>
            <h3>Comfortable workspace</h3>
            <p>
              We’ve made sure you feel at home while working, with a
              well-ventilated office and comfortable furniture.
            </p>
          </div>
        </div>
        {/* <div className='row benefits-wrap'>
          <div className='col-md-6 benefits-text'>
            <h3>Why Join our Team?</h3>
          </div>
          <div className='col-md-6 benefits-img'>
            <img src={BenefitsImg} alt='img' />
          </div>
        </div> */}
      </div>

      {/* Job Openings */}
      <AvailableJobs jobsData={jobs} />
      <NoJobs />

      {/* Empowered Section */}
      <Ecosystem />

      {/* Newsletter */}
      <Subscribe />
      <Footer />
    </>
  );
};

export default Career;
