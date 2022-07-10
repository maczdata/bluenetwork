import React from "react";
import "./index.css";
import HeroAboutImg from "../../assets/rafiki.svg";
import VisionImg from "../../assets/vision.svg";
import MissionImg from "../../assets/mission.svg";
import GrowthImg from "../../assets/growth.svg";
import TeamMember1Img from "../../assets/team-member1.svg";
import TeamMember2Img from "../../assets/team-member2.svg";
import TeamMember3Img from "../../assets/team-member3.svg";
import TeamMember4Img from "../../assets/team-member4.svg";
import TeamMember5Img from "../../assets/team-member5.svg";
import FaceBookIcon from "../../assets/card-facebook.svg";
import TwitterIcon from "../../assets/card-twitter.svg";
import InstagramIcon from "../../assets/card-instagram.svg";
import TestimonySlider from "../../components/sliders/TestimonySlider/TestimonySlider";
import Culture from "../../components/culture/Culture";
import Subscribe from "../../components/subscribe/Subscribe";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import CtaBtn from "../../components/CallToAction/Cta-btns/CtaBtn";

const About = () => {
  return (
    <>
      <Header />
      <div>
        {/* Hero section */}
        <div className='hero-bg-utility mt-5'>
          <div className='container'>
            <div className='row'>
              <div className='col-md-6 hero-home-intro'>
                <h1>
                  We are Building a Digital Ecosystem that empowers individuals
                  & businesses in Nigeria
                </h1>
                <p className=''>
                  We believe that anything is possible, and the life of
                  Nigerians can be easier; we are working hard towards creating
                  these digital possibilities.
                </p>
                <CtaBtn />
              </div>
              <div className='col-md-6 mt-5 mt-lg-0'>
                <img src={HeroAboutImg} alt='img' className='img-fluid' />
              </div>
            </div>
          </div>
        </div>

        {/* Vision section */}
        <div className='vision bg-utility-right'>
          <div className='container mx-auto'>
            <div className='d-flex flex-column justify-content-center align-items-center text-center'>
              <div className='col-lg-4 mt-5 '>
                <img src={VisionImg} alt='img' className='' />
                <h4 className='mt-3 fw-bold'>Our Vision</h4>
                <p>
                  We are empowering Nigerians digitally by providing a result of
                  digital services that help meet the needs of businesses and
                  individuals.
                </p>
              </div>
              <div className='col-lg-4 my-3'>
                <img src={MissionImg} alt='img' className='' />
                <h4 className='fw-bold'>Our Mission</h4>
                <p>
                  At Blue-D Services, our mission is to create an ecosystem of
                  digital services that equip and empower individuals to achieve
                  their goals with innovative and modern technologies.
                </p>
              </div>
              <div className='col-lg-4 my-3'>
                <img src={GrowthImg} alt='img' className='' />
                <h4 className='fw-bold'>Our Culture</h4>
              </div>
            </div>
            <div className=''>
              <Culture />
            </div>
          </div>
        </div>
        {/* Team Section */}
        <div className='teams-img'></div>
        <div className='container py-5'>
          <h1 className='text-center mt-5 fw-bold'>Meet The Team</h1>
          <div className='d-flex flex-lg-row flex-column align-items-center justify-content-lg-between mt-5 pt-5'>
            <div className=' d-flex flex-column column-one'>
              <div className='card team-card'>
                <div className=' mx-auto h-50 px-3 mt-5 team-img'>
                  <img src={TeamMember1Img} alt='a person' />
                </div>
                <div className='card-bottom px-3 mt-3 text-center'>
                  <h4 className='secondary-blue'>Team Member Name</h4>
                  <h6 className='position'>Position</h6>
                </div>
                <div className=' d-flex flex-row justify-content-between align-items-center card-ellipse'>
                  <h5 className='text-white'>Connect with me!</h5>
                  <img
                    src={FaceBookIcon}
                    alt='facebook icon'
                    className='card-icons'
                  />
                  <img
                    src={TwitterIcon}
                    alt='twitter icon'
                    className='card-icons'
                  />
                  <img
                    src={InstagramIcon}
                    alt='instagram icon'
                    className='card-icons'
                  />
                </div>
              </div>
              <div className='card team-card'>
                <div className=' mx-auto h-50 px-3 mt-5 team-img'>
                  <img src={TeamMember2Img} alt='a person' />
                </div>
                <div className='card-bottom px-3 mt-3 text-center'>
                  <h4 className='secondary-blue'>Team Member Name</h4>
                  <h6 className='position'>Position</h6>
                </div>
                <div className=' d-flex flex-row justify-content-between align-items-center card-ellipse'>
                  <h5 className='text-white'>Connect with me!</h5>
                  <img
                    src={FaceBookIcon}
                    alt='facebook icon'
                    className='card-icons'
                  />
                  <img
                    src={TwitterIcon}
                    alt='twitter icon'
                    className='card-icons'
                  />
                  <img
                    src={InstagramIcon}
                    alt='instagram icon'
                    className='card-icons'
                  />
                </div>
              </div>
            </div>
            <div className='d-flex align-self-center align-items-center justify-content-center column-two'>
              <div className='card team-card'>
                <div className=' mx-auto h-50 px-3 mt-5 team-img'>
                  <img src={TeamMember3Img} alt='a person' />
                </div>
                <div className='card-bottom px-3 mt-3 text-center'>
                  <h4 className='secondary-blue'>Team Member Name</h4>
                  <h6 className='position'>Position</h6>
                </div>
                <div className=' d-flex flex-row justify-content-between align-items-center card-ellipse'>
                  <h5 className='text-white'>Connect with me!</h5>
                  <img
                    src={FaceBookIcon}
                    alt='facebook icon'
                    className='card-icons'
                  />
                  <img
                    src={TwitterIcon}
                    alt='twitter icon'
                    className='card-icons'
                  />
                  <img
                    src={InstagramIcon}
                    alt='instagram icon'
                    className='card-icons'
                  />
                </div>
              </div>
            </div>
            <div className='d-flex flex-column column-three'>
              <div className='card team-card'>
                <div className=' mx-auto h-50 px-3 mt-5 team-img'>
                  <img src={TeamMember4Img} alt='a person' />
                </div>
                <div className='card-bottom px-3 mt-3 text-center'>
                  <h4 className='secondary-blue'>Team Member Name</h4>
                  <h6 className='position'>Position</h6>
                </div>
                <div className=' d-flex flex-row justify-content-between align-items-center card-ellipse'>
                  <h5 className='text-white'>Connect with me!</h5>
                  <img
                    src={FaceBookIcon}
                    alt='facebook icon'
                    className='card-icons'
                  />
                  <img
                    src={TwitterIcon}
                    alt='twitter icon'
                    className='card-icons'
                  />
                  <img
                    src={InstagramIcon}
                    alt='instagram icon'
                    className='card-icons'
                  />
                </div>
              </div>
              <div className='card team-card'>
                <div className=' mx-auto h-50 px-3 mt-5 team-img'>
                  <img src={TeamMember5Img} alt='a person' />
                </div>
                <div className='card-bottom px-3 mt-3 text-center'>
                  <h4 className='secondary-blue'>Team Member Name</h4>
                  <h6 className='position'>Position</h6>
                </div>
                <div className=' d-flex flex-row justify-content-between align-items-center card-ellipse'>
                  <h5 className='text-white'>Connect with me!</h5>
                  <img
                    src={FaceBookIcon}
                    alt='facebook icon'
                    className='card-icons'
                  />
                  <img
                    src={TwitterIcon}
                    alt='twitter icon'
                    className='card-icons'
                  />
                  <img
                    src={InstagramIcon}
                    alt='instagram icon'
                    className='card-icons'
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Empowered Section */}
        <Ecosystem />

        {/* Testimonial Section */}
        <TestimonySlider />

        {/*Newsletter Section*/}
        <Subscribe />
      </div>
      <Footer />
    </>
  );
};

export default About;
