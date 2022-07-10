import React, { useEffect, useState } from "react";
import "./index.scss";
import IMG2 from "../../../assets/wallet 2.svg";
import { useDispatch, useSelector } from "react-redux";
import RecentTransactions from "../../../components/RecentTransactions";
import WalletBallance from "../dashboardHome/WalletBallance";
import Fade from "../../../components/advert";
import {
  addOns,
  brandingCategory,
  brandingFeature,
  getAllServices,
  getPreview,
  getVariant,
  getWebPayment,
  getWebPreview,
  VARIANT_FAIL,
  VARIANT_SUCCESS,
} from "../../../redux/actions/service";
import WebModal from "./WebModal";
import axios from "axios";
import WebPreviewModal from "./WebPreviewModal";
import SuccessOrFailModal from "../dashboardExchange/modal";

const DashboardWeb = () => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const bdWeb = servicesData.responesThree?.data?.data;
  const bdWebData = servicesData?.variant?.data;
  const allFeaturedata = servicesData?.featureSuccess?.data;
  const allAdons = servicesData?.addOns?.data?.addons;
  const price = servicesData?.addOns?.data?.formatted_price;
  const auth = useSelector((state) => state.auth);
  const bal = auth?.data?.wallet_balance;
  const data = servicesData?.previewSuccess?.amount_to_pay;
  const successOrFail = servicesData?.webPaymentSuccess;
 
  // fields
  const [serviceType, setServiceType] = useState("web-design");
  const [image, setImage] = useState(null);
  const [website_title, setWebsite_title] = useState("");
  const [domain_name, setDomain_name] = useState("");
  const [description, setDescription] = useState("");
  const [brand_color, setBrand_color] = useState("");
  const [additional_information, setAdditional_information] = useState("");
  const [post_title, setPost_title] = useState("");
  const [content_goals, setContent_goals] = useState("");
  const [type, setType] = useState([]);
  const is_preview = true;
  const [categoryKey, setCategoryKey] = useState("");
  
  // state to display or show contents
  const [toggleExchange, setToggleExchange] = useState(false);
  const [webCatModal, setWebCatModal] = useState(false);
  const [bdBillsIndex, setBdBillsIndex] = useState("web-design");
  const [add_ons, setAdd_ons] = useState([]);
  const [finalInfo, setFinalInfo] = useState({});
  const [no_pages, setNo_pages] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("");
  const [showWebPreviewModal, setShowWebPreviewModal] = useState(false);
  const [selectedCategoryImage, setSelectedCategoryImage] = useState("");

  useEffect(() => {
    dispatch(getAllServices());
    dispatch(brandingCategory(serviceType));
    getVariants(serviceType);
  }, [serviceType]);

  const closeGiftCardModal = () => {
    setWebCatModal(false);
  };

  const handleSelectedDetails = () => {
    setToggleExchange(true);
  };

  const handleChange = (e) => {
    const name = e.target.name;
    const value = e.target.value;
    setType({ ...type, [name]: value });
  };

  const handleCategorySelect = (web) => {
    setCategoryKey(web.key);
    setSelectedCategory(web.id);
    setSelectedCategoryImage(web.icon);
    dispatch(addOns(web.key));
    dispatch(brandingFeature(web.id));
  };

  const closePreviewModal = () => {
    setShowWebPreviewModal(false);
  };

  const fileSlectedHandler = (e) => {
    setImage(e.target.files);
  };

  const getVariants = async (key) => {
    const token = localStorage.getItem("access_token");
    const config = {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    };
    try {
      const res = await axios.get(
        `https://staging-api.bluednetwork.com/front_api/service/single/${key}/variants`,
        config
      );
      dispatch({
        type: VARIANT_SUCCESS,
        payload: res?.data,
      });
      console.log("VARIANT", res?.data);
    } catch (error) {
      console.log("VARIANT error", error.message);
      dispatch({
        type: VARIANT_FAIL,
      });
      return error;
    }
  };


  const handleSubmit = (e) => {
    e.preventDefault();
    let images = [...image];
    let formData = new FormData();
    images.forEach((image, i) => formData.append(`design_document[]`, image));

    dispatch(
      getWebPreview({
        website_title,
        domain_name,
        description,
        brand_color,
        additional_information,
        post_title,
        content_goals,
        type,
        is_preview,
        serviceType,
        service_key: categoryKey,
      })
    );
    setShowWebPreviewModal(true);
  };

  const handlePaymentSuccess = () => {
    let images = [...image];
    let formData = new FormData();
    images.forEach((image, i) => formData.append(`design_document[]`, image));

    if (bal < data) {
      // setShowWalletButton(true);
      return;
    } else {
      dispatch(
        getWebPayment({
          website_title,
          domain_name,
          description,
          brand_color,
          additional_information,
          post_title,
          content_goals,
          type,
          is_preview,
          serviceType,
          service_key: categoryKey,
          "design_document[]": formData,
        })
      );
      setTimeout(() => {
        setShowWebPreviewModal(false);
        setWebsite_title("");
        setDomain_name("");
        setDescription("");
        setBrand_color("");
        setAdditional_information("");
        setPost_title("");
        setContent_goals("");
        setImage("");
      }, 1000);
    }
  };

  return (
    <div>
      {servicesData?.iswebPaymentSuccess && (
        <SuccessOrFailModal data={successOrFail} />
      )}

{console.log({bdWebData})}
      {webCatModal && (
        <WebModal
          data={bdWebData}
          closeGiftCardModal={closeGiftCardModal}
          handleCategorySelect={handleCategorySelect}
          handleSelectedDetails={handleSelectedDetails}
          // selectedCategory={selectedCategory}
        />
      )}

      {showWebPreviewModal && (
        <WebPreviewModal
          handlePaymentSuccess={handlePaymentSuccess}
          closePreviewModal={closePreviewModal}
          // showWalletButton={showWalletButton}
        />
      )}

      <div className='barner__container mt-3'>
        <Fade />
      </div>
      <div className='display_on_small_screen_web'>
        <WalletBallance />
      </div>
      <div className='first__section'>
        <div className='web_buttons'>
          {bdWeb &&
            bdWeb.map((web, index) => (
              <button
                key={index}
                className={
                  bdBillsIndex === web.key ? "bill-tab-active" : "bill-tab"
                }
                onClick={() => {
                  setServiceType(web.key);
                  setBdBillsIndex(web.key);
                  dispatch(brandingCategory(serviceType));
                  getVariants(serviceType);
                  setToggleExchange(false);
                }}
              >
                {web.title}
              </button>
            ))}

          {!toggleExchange ? (
            <>
              {bdBillsIndex === "web-design" ? (
                <div className='web_category_selection '>
                  <h4 className='web_header'>Web Design</h4>
                  <p className='select_web_supplier'>
                    Select a category to get started.
                  </p>
                  <div className='choose__modalbtn'>
                    <button
                      className='choose__modal__btn'
                      onClick={() => {
                        setWebCatModal(true);
                      }}
                    >
                      Choose Category
                    </button>
                  </div>
                </div>
              ) : bdBillsIndex === "social-media-management" ? (
                <div className='web_category_selection'>
                  <h4 className='web_header'>Social Media Management</h4>
                  <p className='select_web_supplier'>
                    Select a category to get started.
                  </p>
                  <div className='choose__modalbtn'>
                    <button
                      className='choose__modal__btn'
                      onClick={() => setWebCatModal(true)}
                    >
                      Choose Category
                    </button>
                  </div>
                </div>
              ) : (
                bdBillsIndex === "content-writing" && (
                  <div className='web_category_selection'>
                    <h4 className='web_header'>Content Writing</h4>
                    <p className='select_web_supplier'>
                      Select a category to get started.
                    </p>
                    <div className='choose__modalbtn'>
                      <button
                        className='choose__modal__btn'
                        onClick={() => setWebCatModal(true)}
                      >
                        Choose Category
                      </button>
                    </div>
                  </div>
                )
              )}
            </>
          ) : (
            <>
              <form onSubmit={handleSubmit}>
                <div className='categories__details'>
                  <div className='first__details'>
                    <div className='web_category_selection '>
                      {/* <h4 className='web_header'>Web Design</h4>
                      <p className='select_web_supplier'>
                        Select a category to get started.
                      </p> */}
                      <div className='choose__modalbtns'>
                        <img src={selectedCategoryImage} alt='' />
                        <button
                          className='choose__modal__btns'
                          onClick={() => {
                            setShowWebPreviewModal(false);
                            setWebCatModal(true);
                          }}
                        >
                          Choose Category
                        </button>
                      </div>
                    </div>

{console.log({allFeaturedata})}
                    <div className='features__wrapper'>
                      {allFeaturedata?.map((data) => (
                        <>
                          <p>{data.title}</p>
                          <div className='features__container'>
                            {data?.featurize_values?.map((item) => (
                              <div className='m-2'>
                                <label className='mx-2'>{item?.title}</label>
                                <input
                                  type='radio'
                                  name={data.slug}
                                  value={item.id}
                                  onChange={(e) => handleChange(e)}
                                />
                              </div>
                            ))}
                          </div>
                        </>
                      ))}
                    </div>
                    <div className='addons__wrapper'>
                      {allAdons?.map((data) => (
                        <>
                          <h2>Addons</h2>
                          <p>
                            Select extra exciting fearures you would like to add
                            to your order.
                          </p>
                          <div className='single__addons'>
                            <label>{data?.title}</label>
                            <input
                              type='checkbox'
                              name={data.slug}
                              value={data.id}
                              // onChange={(e) => handleAdd_ons(e)}
                            />
                            <p className='single__price'>
                              {data?.formatted_price}
                            </p>
                            <p className='single__desc'>{data?.description}</p>
                          </div>
                        </>
                      ))}
                    </div>

                    {/* <div className='payments'>
                      <span className='payments__text'>You will pay</span>
                      <span className='payments__amount'>{price}</span>
                    </div> */}
                  </div>

                  <div className='second__details'>
                    {(categoryKey === "content-writing-social-media" ||
                      categoryKey === "content-writing-blog-post") && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='post_title'
                            name='post_title'
                            onChange={(e) => setPost_title(e.target.value)}
                            value={post_title}
                            rows='4'
                            placeholder='post title'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='content_goals'
                            name='content_goals'
                            onChange={(e) => setContent_goals(e.target.value)}
                            value={content_goals}
                            rows='4'
                            placeholder='content goals'
                            required
                          />
                        </div>
                      </>
                    )}

                    {(categoryKey === "social-media-management-bronze" ||
                      categoryKey === "social-media-management-silver" ||
                      categoryKey === "social-media-management-gold") && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='brand_color'
                            name='brand_color'
                            onChange={(e) => setBrand_color(e.target.value)}
                            value={brand_color}
                            rows='4'
                            placeholder='brand color'
                            required
                          />
                        </div>
                      </>
                    )}

                    {(categoryKey === "web-design-blog" ||
                      categoryKey === "web-design-corporate" ||
                      categoryKey === "web-design-ecommerce") && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='website_title'
                            name='website_title'
                            onChange={(e) => setWebsite_title(e.target.value)}
                            value={website_title}
                            rows='4'
                            placeholder='website title'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='domain_name'
                            name='domain_name'
                            onChange={(e) => setDomain_name(e.target.value)}
                            value={domain_name}
                            rows='4'
                            placeholder='domain name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='brand_color'
                            name='brand_color'
                            onChange={(e) => setBrand_color(e.target.value)}
                            value={brand_color}
                            rows='4'
                            placeholder='brand color'
                            required
                          />
                        </div>
                      </>
                    )}
                    <div className='quick_service_input'>
                      <textarea
                        id='description'
                        name='description'
                        onChange={(e) => setDescription(e.target.value)}
                        value={description}
                        rows='4'
                        placeholder='Describe your project'
                        required
                      />
                    </div>

                    <div className='quick_service_input'>
                      <textarea
                        id='description'
                        name='additional_information'
                        onChange={(e) =>
                          setAdditional_information(e.target.value)
                        }
                        value={additional_information}
                        rows='4'
                        placeholder='Additional information'
                      />
                    </div>

                    <div className='quick_service_input'>
                      <input
                        multiple
                        // className='file-input'
                        type='file'
                        onChange={fileSlectedHandler}
                        required
                      />
                    </div>
                    {/* {!spinner && <button type='submit'>Continue</button>}
                        {spinner && (
                          <button type='submit' disabled>
                            <Spinner /> Loading...
                          </button>
                        )} */}
                    <button type='submit'>Continue</button>
                  </div>
                </div>
              </form>
            </>
          )}
        </div>

        <div className='wallet__information'>
          <div className='hide_on_small_screen'>
            <WalletBallance />
          </div>
          <div className='recent__transaction'>
            <div className='first'>
              <span>
                <img src={IMG2} alt='' />
              </span>
              <p>Recent Transactions</p>
            </div>

            <div className='second'>
              <RecentTransactions />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DashboardWeb;
