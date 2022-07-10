import React from "react";
// Mine
import ServiceEllipse from "../../assets/service-ellipse.svg";
import ServiceVector from "../../assets/service-card-vector.svg";
import ArrowRight from "../../assets/white-right-arrow.svg";

import "./index.css";

// const serviceCards = [
//   {
//     id: 1,
//     title: "BUSINESS AND BRAND NAMING",
//     content:
//       "We’re excited to work with you on your business and brand naming endeavour. Do you want a short, catchy name? You’d prefer a creative one? It doesn’t matter what your business is about, trust us to give you the very best name that suits your preferences. After this phase, we can proceed to get your business registered with CAC; it’s a complete package!",
//   },
//   {
//     id: 2,
//     title: "BRAND IDENTITY SYSTEMS",
//     content:
//       "Identity systems like logos, ID cards, business cards and letterheads are crucial ways to represent your brand. It’s vital that you get a well-executed representation of what you’re about. These systems help tell your prospects a visual story, and if executed correctly, positively affects how you are perceived. Let us create the perfect brand identity system for you.",
//   },
//   {
//     id: 3,
//     title: "UI/UX DESIGN",
//     content:
//       "Do you need to give your users a smooth experience and top-notch satisfaction? You can trust our team of highly-skilled personnel to provide you with exactly what you want. With a good UI/UX design in place, your customers navigate easily, and in a short time, the number of users (and repeat users) shoot up. Everyone wants technology that isn’t too demanding, after all.",
//   },
//   {
//     id: 4,
//     title: "SOCIAL MEDIA & ADVERTISING DESIGNS",
//     content:
//       "With how powerful social media has become, it has turned into an effective advertising platform, and we think you should join the bandwagon, but not half-prepared. Let’s work together to create professional and eye-catching designs that advertise your event, services or sales efficiently. Apart from social media designs, we will be pleased also to help you advertise “offline”, whether it’s with a flier, a banner or a billboard.",
//   },
//   {
//     id: 5,
//     title: "INFOGRAPHICS & PRESENTATION DESIGNS",
//     content:
//       "The goal of infographics and presentation designs is to help your audience pay attention to your message, feel it, understand it and remember it. With bad designs, the opposite happens, and we do not want this for you. So, if you need seminar designs, an infographic design, training designs or pitching designs, we’re up and ready to create attractive designs for you.",
//   },
//   {
//     id: 6,
//     title: "WEB GRAPHICS",
//     content:
//       "It’s one thing to have a website, and it’s another for your users to appreciate your website. If you would love your users to enjoy visiting your site, let’s help. Visually stimulating web graphics will not only improve your user’s appreciation but also enhance your site’s overall aesthetics, professionalism and brand value. What are you waiting for?",
//   },
//   {
//     id: 7,
//     title: "PACKAGE DESIGNS",
//     content:
//       "Catchy designs will always attract more attention and potentially more customers. This is precisely what you’re gunning for with your product, right? We can work together to make sure your brand story comes to life in the most attractive way, whether it’s a brochure, a book, boxes, or paper bags.",
//   },
//   {
//     id: 8,
//     title: "READY MADE DESIGNS",
//     content:
//       "Finished designs are waiting for you to pick from and customise with your own details. This saves time and comes at a lesser price. Sounds good, right?",
//   },
// ];

const Services = () => {
  return (
    <div className='service-container'>
      <div className='culture-contents'>
        <div className='wrapper-div'>
          {/* Cards */}
          {/* {serviceCards.map((card) => ( */}
          <div className='service-cards'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>BUSINESS AND BRAND NAMING</h2>
                <p className=''>
                  With how powerful social media has become, it has turned into
                  an effective advertising platform, and we think you should
                  join the bandwagon, but not half-prepared. Let’s work together
                  to create professional and eye-catching designs that advertise
                  your event, services or sales efficiently. Apart from social
                  media designs, we will be pleased also to help you advertise
                  “offline”, whether it’s with a flier, a banner or a billboard.
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>

          <div className='service-cards push-up-card'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>BRAND IDENTITY SYSTEMS</h2>
                <p className=''>
                  Identity systems like logos, ID cards, business cards and
                  letterheads are crucial ways to represent your brand. It’s
                  vital that you get a well-executed representation of what
                  you’re about. These systems help tell your prospects a visual
                  story, and if executed correctly, positively affects how you
                  are perceived. Let us create the perfect brand identity system
                  for you.
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>

          <div className='service-cards'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>UI/UX DESIGN</h2>
                <p className=''>
                  Do you need to give your users a smooth experience and
                  top-notch satisfaction? You can trust our team of
                  highly-skilled personnel to provide you with exactly what you
                  want. With a good UI/UX design in place, your customers
                  navigate easily, and in a short time, the number of users (and
                  repeat users) shoot up. Everyone wants technology that isn’t
                  too demanding, after all.
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>

          

          <div className='service-cards '>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>
                  SOCIAL MEDIA & ADVERTISING DESIGNS
                </h2>
                <p className=''>
                  With how powerful social media has become, it has turned into
                  an effective advertising platform, and we think you should
                  join the bandwagon, but not half-prepared. Let’s work together
                  to create professional and eye-catching designs that advertise
                  your event, services or sales efficiently. Apart from social
                  media designs, we will be pleased also to help you advertise
                  “offline”, whether it’s with a flier, a banner or a billboard.
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>

          <div className='service-cards push-up-card'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>
                  INFOGRAPHICS & PRESENTATION DESIGNS
                </h2>
                <p className=''>
                  The goal of infographics and presentation designs is to help
                  your audience pay attention to your message, feel it,
                  understand it and remember it. With bad designs, the opposite
                  happens, and we do not want this for you. So, if you need
                  seminar designs, an infographic design, training designs or
                  pitching designs, we’re up and ready to create attractive
                  designs for you.
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>

          <div className='service-cards '>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>WEB GRAPHICS</h2>
                <p className=''>
                  It’s one thing to have a website, and it’s another for your
                  users to appreciate your website. If you would love your users
                  to enjoy visiting your site, let’s help. Visually stimulating
                  web graphics will not only improve your user’s appreciation
                  but also enhance your site’s overall aesthetics,
                  professionalism and brand value. What are you waiting for?
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>
          <div className='service-cards'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>BUSINESS AND BRAND NAMING</h2>
                <p className=''>
                  With how powerful social media has become, it has turned into
                  an effective advertising platform, and we think you should
                  join the bandwagon, but not half-prepared. Let’s work together
                  to create professional and eye-catching designs that advertise
                  your event, services or sales efficiently. Apart from social
                  media designs, we will be pleased also to help you advertise
                  “offline”, whether it’s with a flier, a banner or a billboard.
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>

          <div className='service-cards push-up-card'>
            <div className=' service-card'>
              <div className='service-card-blue-circle'>
                <img src={ServiceVector} alt='Service image' />
              </div>
              <div className=' service-card-content'>
                <h2 className='secondary-blue'>READY MADE DESIGNS</h2>
                <p className=''>
                  Finished designs are waiting for you to pick from and
                  customise with your own details. This saves time and comes at
                  a lesser price. Sounds good, right?
                </p>
              </div>
            </div>
            <div className=' d-flex justify-content-between align-items-center service-bottom-svg text-white'>
              <h5>GET STARTED</h5>
              <img src={ArrowRight} alt='white right arrow' />
            </div>
          </div>
          {/* ))} */}
        </div>
      </div>
    </div>
  );
};

export default Services;
