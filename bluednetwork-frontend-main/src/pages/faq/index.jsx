import Search from "../../components/search";
import Subscribe from "../../components/subscribe/Subscribe";
import Collection from "../../assets/collections.svg";
import Avatar from "../../assets/avatar.svg";
import "./index.css";
import { useState } from "react";
import { 
  // collectionData, 
  data, collectionDataQuestion } from "./data";
import Header from "../../components/header/Header";
import Footer from "../../components/footer/Footer";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import Faqs from "../../components/faq/Faqs";

function FAQ() {
  const [showCollection, setShowCollection] = useState(false);
  const [showCollectionQuestion, setShowCollectionQuestion] = useState(false);

  return (
    <>
      <Header />
      <div className='faq'>
        <div className='faq-hero career-wrapper'>
          <h1 className=''>Frequently Asked Questions</h1>
          <p className='col-lg-4 mx-auto'>
            Get answers to all your questions about Blue-D services and and
            benefit from our platform.
          </p>
          {/* <Search /> */}
        </div>
        <div className='faq-accordion py-5'>
          <div className='container'>
            {!showCollection && (
              <>
                {/* <div
                  class='accordion accordion-flush col-md-8 mx-auto '
                  id='accordionPanelsStayOpenExample'
                >
                  {data.map((item, index) => (
                    <div class='accordion-item' key={index}>
                      <h2
                        class='accordion-header'
                        id={`panelsStayOpen-headingOne${index}`}
                      >
                        <button
                          class='accordion-button'
                          type='button'
                          data-bs-toggle='collapse'
                          data-bs-target={`#panelsStayOpen-collapseOne${index}`}
                          aria-expanded='false'
                          aria-controls={`panelsStayOpen-collapseOne${index}`}
                        >
                          {item.question}
                        </button>
                      </h2>
                      <div
                        id={`panelsStayOpen-collapseOne${index}`}
                        class='accordion-collapse collapse show'
                        aria-labelledby='panelsStayOpen-headingOne'
                      >
                        <div class='accordion-body'>{item.answer}</div>
                      </div>
                    </div>
                  ))}
                </div> */}
                <Faqs />
                <p className='text-center mt-5 text-bold'>
                  Cant find your question?
                </p>
                <div className='faq-cta-btns d-flex justify-content-center align-items-top'>
                  <button
                    className='one'
                    onClick={() => setShowCollection(true)}
                  >
                    Show me More
                  </button>
                </div>
              </>
            )}

            {!showCollectionQuestion && showCollection && (
              <div className='collections col-md-8 mx-auto mt-5'>
                <div className='d-flex mx-auto text-center go__back'>
                  <span onClick={() => setShowCollection(false)}>
                    Frequently Asked Questions
                  </span>{" "}
                  <div className='mx-2'>></div>{" "}
                  <span className='fw-bold'>Collections</span>
                </div>
                <div className='collections-card py-2 px-4 mt-3'>
                  <div className='collections-card-circle mr-4'>
                    <img
                      src={Collection}
                      className='img-fluid'
                      alt='collection'
                    />
                  </div>
                  <div className='collection-name '>
                    Collections <span>8</span>
                  </div>
                </div>
                {/* {collectionData.map((item, index) => (
                  <div
                    className='collections-card-bg-white  py-2 px-4 mt-5'
                    key={index}
                    onClick={() => setShowCollectionQuestion(true)}
                  >
                    <div className='collections-card-circle mr-4'>
                      <img
                        src={Avatar}
                        className='img-fluid'
                        alt='collection'
                      />
                    </div>
                    <div className='collections-card-bg-white-left ml-5'>
                      <div className='collections-card-bg-white-left-title'>
                        {item.title}
                      </div>
                      <div className='d-flex justify-content-between '>
                        <div>
                          By <span className='fw-bold'>{item.name}</span>
                        </div>
                        <div>
                          <span className='fw-bold'>
                            {item.numberOfArticle}
                          </span>{" "}
                          Articles
                        </div>
                      </div>
                    </div>
                  </div>
                ))} */}
              </div>
            )}

            {showCollectionQuestion && showCollection ? (
              <div className='col-md-8 mx-auto mt-5'>
                <div className='d-flex mx-auto text-center go__back'>
                  Frequently Asked Questions <div className='mx-2'>></div>{" "}
                  <span onClick={() => setShowCollectionQuestion(false)}>
                    Collections
                  </span>{" "}
                  <div className='mx-2'>></div>{" "}
                  {/* <div className='fw-bold'>Secure Gift Cards Transactions</div> */}
                </div>
                {/* <div className='collections-card-bg-white  py-2 px-4 mt-5'>
                  <div className='collections-card-circle mr-4'>
                    <img src={Avatar} className='img-fluid' alt='collection' />
                  </div>
                  <div className='collections-card-bg-white-left ml-5'>
                    <div className='collections-card-bg-white-left-title'>
                      {collectionData[0].title}
                    </div>
                    <div className='d-flex justify-content-between '>
                      <div>
                        By{" "}
                        <span className='fw-bold'>
                          {collectionData[0].name}
                        </span>
                      </div>
                      <div>
                        <span className='fw-bold'>
                          {collectionData[0].numberOfArticle}
                        </span>{" "}
                        Articles
                      </div>
                    </div>
                  </div>
                </div> */}
                <div className='collections-card-question'>
                  {collectionDataQuestion.map((item, index) => (
                    <div key={index} className='mt-5'>
                      <div className='mb-3 fw-bold'>
                        {index + 1}. {item.question}
                      </div>
                      <div>{item.answers}</div>
                      <div className='helpful__post'>
                        <p className='mt-3'>
                          “was this post helpful ?”{" "}
                          <span className='text-primary'>“Yes”</span> or{" "}
                          <span className='text-primary'>“No”</span>{" "}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            ) : null}
          </div>
        </div>

        {/* Empowered Section */}
        <Ecosystem />
        {/* <div className='empowered bg-utility-left'>
          <div className='container m p'>
            <div className='col-md-7 hero-home-intro'>
              <h1 className='text-center mb-4'>
                We are Building a Digital Ecosystem that empowers individuals &
                businesses in Nigeria
              </h1>
              <p className='col-md-7 mx-auto text-center'>
                We are empowering Nigerians digitally by providing a nexus of
                digital services that help meet the needs of businesses and
                individuals.
              </p>
              <div className='about-cta-btns d-flex justify-content-center align-items-center '>
                <button className='one'>Get Started</button>
                <button className='two'>Download App</button>
              </div>
            </div>
          </div>
        </div> */}

        {/*Newsletter Section*/}
        <Subscribe />
      </div>
      <Footer />
    </>
  );
}

export default FAQ;
