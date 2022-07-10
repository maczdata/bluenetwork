import React, { useEffect, useState } from "react";
import "./index.scss";
import Banner from "../../../assets/Frame 1.png";
import IMG2 from "../../../assets/credit-card.svg";
import WalletBallance from "../dashboardHome/WalletBallance";
import RecentTransactions from "../../../components/RecentTransactions";
import { FaSpinner } from "react-icons/fa";
import { useDispatch, useSelector } from "react-redux";
import {
  addOns,
  brandingCategory,
  brandingFeature,
  getAllServices,
  getBrandingPayment,
  getPreview,
  getPreviewBranding,
  VARIANT_FAIL,
  VARIANT_SUCCESS,
} from "../../../redux/actions/service";
import BrandingModal from "./BrandingModal";
import PreviewModal from "./PreviewModal";
import SuccessOrFailModal from "../dashboardExchange/modal";
import { loadUser } from "../../../redux/actions/auth";
import Fade from "../../../components/advert";
import axios from "axios";
import rewriteUrl from "../../../helper/rewriteUrl";

const DashboardBranding = () => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const bdBranding = servicesData.responesFour?.data?.data;

  console.log("brand", servicesData);
  const [bdBillsIndex, setBdBillsIndex] = useState("graphic-design");

  const [toggleExchange, setToggleExchange] = useState(false);
  const [showBrandingModal, setShowBrandingModal] = useState(false);
  const [brandingQuery, setBrandingQuery] = useState("graphic-design");
  const brandingCategoryData =
    servicesData?.getBrandingCategory?.data?.children;
  const [selectedCategory, setCSelectedCategory] = useState("");
  const categoryInfo =
    brandingCategoryData &&
    Object.entries(brandingCategoryData).map(([, value]) => value);

  // new changes
  const brandingCategoryInfo = servicesData?.variant?.data;
  const add__ons = brandingCategoryInfo;
  console.log("add__ons", add__ons);

  const allFeaturedata = servicesData?.featureSuccess?.data;
  const allAdons = servicesData?.addOns?.data?.addons;
  const successOrFail = servicesData?.brandingPaymentSuccess;
  const spinner = servicesData.loading;
  const price = servicesData?.addOns?.data?.formatted_price;

  const [showSelectedDetails, setShowSelectedDetails] = useState(false);
  const [showPreviewModal, setShowPreviewModal] = useState(false);

  // fields
  const [nature_of_ngo, setNature_of_ngo] = useState("");
  const [ngo_name, setNgo_name] = useState("");
  const [ngo_email, setNgo_email] = useState("");
  const [ngo_phone_number, setNgo_phone_number] = useState("");
  const [ngo_address, setNgo_address] = useState("");

  const [name, setName] = useState("");
  const [colors, setColors] = useState("");
  const [brand_color, setBrand_color] = useState("");
  const [business_name, setBusiness_name] = useState("");
  const [business_email, setBusiness_email] = useState("");
  const [nature_of_business, setNature_of_business] = useState("");
  const [business_phone_number, setBusiness_phone_number] = useState("");
  const [phone_number, setPhone_number] = useState("");
  const [full_name, setFull_name] = useState("");
  const [business_address, setBusiness__address] = useState("");
  const [address, setAddress] = useState("");
  const [email, setEmail] = useState("");
  const [additional_information_1, setAdditional_information_1] = useState("");
  const [slogan, setSlogan] = useState("");
  const [description, setDescription] = useState("");
  const [additional_information, setAdditional_information] = useState("");
  const is_preview = true;
  const [serviceType, setServiceType] = useState("graphic-design");
  const [addons, setAddons] = useState("");
  const [type, setType] = useState([]);
  const [image, setImage] = useState(null);
  const [passport, setPassport] = useState(null);
  const [valid_id, setValid_id] = useState(null);
  const [add_ons, setAdd_ons] = useState([]);
  const [signature, setSignature] = useState(null);
  const [finalInfo, setFinalInfo] = useState({});
  const [showSelectedImage, setShowSelectedImage] = useState("");
  const [showSelectedPrice, setShowSelectedPrice] = useState("");
  const [selectedCategoryImage, setSelectedCategoryImage] = useState("");

  const fileSlectedHandler = (e) => {
    setImage(e.target.files[0]);
  };

  const fileSlectedHandler1 = (e) => {
    setPassport(e.target.files[0]);
  };

  const fileSlectedHandler2 = (e) => {
    setValid_id(e.target.files[0]);
  };

  const fileSlectedHandler3 = (e) => {
    setSignature(e.target.files[0]);
  };

  const closeGiftCardModal = () => {
    setShowBrandingModal(false);
    setShowPreviewModal(false);
  };

  const getAddons = (id) => {
    const filteredCategory =
      brandingCategoryInfo &&
      brandingCategoryInfo.find((data) => {
        if (data.id == id) {
          return data;
        }
      });
    // dispatch(addOns(filteredCategory?.key));
    console.log("yeah", filteredCategory.addons);
    setAddons(filteredCategory.addons);
  };

  const handleCategorySelect = (brand) => {
    setShowSelectedImage(brand);
    setShowSelectedPrice(brand.formatted_price);
    setCSelectedCategory(brand.key);
    getAddons(brand.id);
    setSelectedCategoryImage(brand.icon);
    dispatch(brandingFeature(brand.id));
    // dispatch(addOns(brand.key));
  };

  const handleSelectedDetails = () => {
    setToggleExchange(true);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // let images = [...image];
    // let passports = [...passport];
    // let valid_ids = [...valid_id];
    // let signatures = [...signature];

    let formData = new FormData();
    // images.forEach((image, i) =>
    formData.append(`design_document[]`, image);
 
    formData.append(`custom_fields[].nature_of_business`, JSON.stringify(nature_of_business));
    formData.append("is_preview", is_preview);
    formData.append("service_type_slug", "bd-branding");
    // serviceType: "bd_branding",
    // service_key: selectedCategory
    formData.append("variant_key", selectedCategory);
    formData.append("service_key", serviceType);
    // )
    // passports.forEach((passport, i) =>
    formData.append("passport", passport);
    // );
    // valid_ids.forEach((valid_id, i) =>
    formData.append("valid_id", valid_id);
    // );
    // signatures.forEach((signature, i) =>
    formData.append("signature", signature);
    // );

    console.log("fields", {
      nature_of_ngo,
      ngo_name,
      ngo_email,
      ngo_phone_number,
      ngo_address,
      name,
      colors,
      brand_color,
      business_name,
      business_email,
      nature_of_business,
      business_phone_number,
      phone_number,
      full_name,
      business_address,
      address,
      email,
      additional_information_1,
      slogan,
      description,
      additional_information,
      is_preview,
      serviceType,
      formData,
      selectedCategory,
      type,
      add_ons,
    });

    console.log("I am here bro");
    console.log({
      nature_of_ngo,
      ngo_name,
      ngo_email,
      ngo_phone_number,
      ngo_address,
      name,
      colors,
      brand_color,
      business_name,
      business_email,
      nature_of_business,
      business_phone_number,
      phone_number,
      full_name,
      business_address,
      address,
      email,
      additional_information_1,
      slogan,
      description,
      additional_information,
      is_preview,
      serviceType,
      service_key: selectedCategory,
      type,
      add_ons,
      formData,
    })
    dispatch(
      getPreview({
        nature_of_ngo,
        ngo_name,
        ngo_email,
        ngo_phone_number,
        ngo_address,
        name,
        colors,
        brand_color,
        business_name,
        business_email,
        nature_of_business,
        business_phone_number,
        phone_number,
        full_name,
        business_address,
        address,
        email,
        additional_information_1,
        slogan,
        description,
        additional_information,
        is_preview,
        serviceType,
        service_key: selectedCategory,
        type,
        add_ons,
        formData,
      })
    );

    // setFinalInfo({
    //   business_name: name,
    //   brand_colors: colors,
    //   slogan,
    //   description: description,
    //   is_preview,
    //   serviceType,
    //   service_key: categoryKey,
    //   "design_document[]": formData,
    //   add_ons,
    //   type,
    // });

    // setShowPreviewModal(true);
  };

  const auth = useSelector((state) => state.auth);
  const bal = auth?.data?.wallet_balance;
  const data = servicesData?.previewSuccess?.amount_to_pay;
  const [showWalletButton, setShowWalletButton] = useState(false);

  console.log("serviceType", {
    serviceType,
    servicesData,
    brandingCategoryInfo,
  });

  const getVariants = async (serviceType) => {
    const token = localStorage.getItem("access_token");
    const config = {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    };
    try {
      const res = await axios.get(
        `${rewriteUrl()}front_api/service/single/${serviceType}/variants`,
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

  useEffect(() => {
    dispatch(loadUser());
    dispatch(getAllServices());
    // dispatch(brandingCategory(serviceType));
    getVariants(serviceType);
  }, [serviceType]);

  const handlePaymentSuccess = () => {
    let images = [...image];
    let formData = new FormData();
    images.forEach((image, i) => formData.append(`design_document[]`, image));

    if (bal < data) {
      setShowWalletButton(true);
    } else {
      dispatch(
        getBrandingPayment({
          business_name: name,
          brand_color: colors,
          slogan,
          description: description,
          is_preview,
          serviceType,
          // package_key: categoryKey,
          "design_document[]": formData,
          add_ons,
          type,
        })
      );

      setShowPreviewModal(false);
    }
  };

  const handleChange = (e) => {
    const name = e.target.name;
    const value = e.target.value;
    setType({ ...type, [name]: value });
  };

  const handleAdd_ons = (e) => {
    const name = e.target.name;
    const value = e.target.value;
    const alreadyExist = add_ons.filter(
      (item) => Object.keys(item)[0] === name
    );
    const oldInfoWithoutTheNewOne = add_ons.filter(
      (item) => Object.keys(item)[0] !== name
    );

    if (alreadyExist.length > 0)
      return setAdd_ons([...oldInfoWithoutTheNewOne]);

    return setAdd_ons([...add_ons, { [name]: value }]);
  };

  console.log("serviceType", serviceType);

  useEffect(() => {
    dispatch(getAllServices());
    dispatch(brandingCategory(brandingQuery));
  }, []);

  return (
    <div className='dashboard-components'>
      {servicesData?.isbrandingPaymentSuccess && (
        <SuccessOrFailModal data={successOrFail} />
      )}

      {showBrandingModal && (
        <BrandingModal
          data={brandingCategoryInfo}
          closeGiftCardModal={closeGiftCardModal}
          handleCategorySelect={handleCategorySelect}
          handleSelectedDetails={handleSelectedDetails}
          selectedCategory={selectedCategory}
        />
      )}

      {showPreviewModal && (
        <PreviewModal
          handlePaymentSuccess={handlePaymentSuccess}
          closeGiftCardModal={closeGiftCardModal}
          showWalletButton={showWalletButton}
        />
      )}

      <div className='barner__container mt-3'>
        <Fade />
      </div>
      <div className='display_on_small_screen_branding'>
        <WalletBallance />
      </div>
      <div className='first__section'>
        <div className='web_buttons'>
          {bdBranding &&
            bdBranding.map((brand, index) => (
              <button
                key={index}
                className={
                  bdBillsIndex === brand.key ? "bill-tab-active" : "bill-tab"
                }
                onClick={() => {
                  setServiceType(brand.key);
                  setBdBillsIndex(brand.key);
                  // dispatch(brandingCategory(serviceType));
                  getVariants(brand?.key);
                  setToggleExchange(false);
                }}
              >
                {brand.title}
              </button>
            ))}

          {!toggleExchange ? (
            <>
              {bdBillsIndex === "graphic-design" ? (
                <div className='web_category_selection '>
                  <h4 className='web_header'>Graphic Design</h4>
                  <p className='select_web_supplier'>
                    Select a category to get started.
                  </p>
                  <div className='choose__modalbtn'>
                    <button
                      className='choose__modal__btn'
                      onClick={() => {
                        setShowBrandingModal(true);
                      }}
                    >
                      Choose Category
                    </button>
                  </div>
                </div>
              ) : (
                bdBillsIndex === "cac_registration" && (
                  <div className='web_category_selection'>
                    <h4 className='web_header'>CAC Registration</h4>
                    <p className='select_web_supplier'>
                      Select a category to get started.
                    </p>
                    <div className='choose__modalbtn'>
                      <button
                        className='choose__modal__btn'
                        onClick={() => setShowBrandingModal(true)}
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
                      <div className='choose__modalbtns'>
                        <img src={selectedCategoryImage} alt='' />
                        <button
                          className='choose__modal__btns'
                          onClick={() => {
                            // setShowWebPreviewModal(false);
                            // setWebCatModal(true);
                          }}
                        >
                          Choose Category
                        </button>
                      </div>
                    </div>

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
                      {addons?.map((data) => (
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
                              onChange={(e) => handleAdd_ons(e)}
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
                    {selectedCategory === "graphic-design-logo" && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='slogan'
                            name='slogan'
                            onChange={(e) => setSlogan(e.target.value)}
                            value={slogan}
                            rows='4'
                            placeholder='Slogan'
                            type='text'
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
                            type='text'
                            placeholder='brand color'
                            required
                          />
                        </div>
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
                            type='file'
                            onChange={fileSlectedHandler}
                            required
                          />
                        </div>
                      </>
                    )}

                    {selectedCategory === "graphic-design-flier" && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='business_name'
                            name='business_name'
                            onChange={(e) => setBusiness_name(e.target.value)}
                            value={business_name}
                            rows='4'
                            type='text'
                            placeholder='Business name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='slogan'
                            name='slogan'
                            onChange={(e) => setSlogan(e.target.value)}
                            value={slogan}
                            rows='4'
                            type='text'
                            placeholder='Slogan'
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
                            type='text'
                            placeholder='brand color'
                            required
                          />
                        </div>
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
                            type='file'
                            onChange={fileSlectedHandler}
                            required
                          />
                        </div>
                      </>
                    )}

                    {selectedCategory === "graphic-design-banner" && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='business_name'
                            name='business_name'
                            onChange={(e) => setBusiness_name(e.target.value)}
                            value={business_name}
                            rows='4'
                            type='text'
                            placeholder='Business name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='slogan'
                            name='slogan'
                            onChange={(e) => setSlogan(e.target.value)}
                            value={slogan}
                            rows='4'
                            type='text'
                            placeholder='Slogan'
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
                            type='text'
                            placeholder='brand color'
                            required
                          />
                        </div>
                        <div className='quick_service_input'>
                          <textarea
                            id='description'
                            name='description'
                            onChange={(e) => setDescription(e.target.value)}
                            value={description}
                            rows='4'
                            type='text'
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
                            type='file'
                            onChange={fileSlectedHandler}
                            required
                          />
                        </div>
                      </>
                    )}

                    {selectedCategory === "graphic-design-card" && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='business_name'
                            name='business_name'
                            onChange={(e) => setBusiness_name(e.target.value)}
                            value={business_name}
                            rows='4'
                            type='text'
                            placeholder='Business name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='slogan'
                            name='slogan'
                            onChange={(e) => setSlogan(e.target.value)}
                            value={slogan}
                            rows='4'
                            type='text'
                            placeholder='Slogan'
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
                            type='text'
                            placeholder='brand color'
                            required
                          />
                        </div>
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
                            type='file'
                            onChange={fileSlectedHandler}
                            required
                          />
                        </div>
                      </>
                    )}

                    {selectedCategory === "business-name" && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='nature_of_business'
                            name='nature_of_business'
                            onChange={(e) =>
                              setNature_of_business(e.target.value)
                            }
                            value={nature_of_business}
                            rows='4'
                            type='text'
                            placeholder='Nature of Business'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_name'
                            name='business_name'
                            onChange={(e) => setBusiness_name(e.target.value)}
                            value={business_name}
                            rows='4'
                            type='text'
                            placeholder='Business Name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_email'
                            name='business_email'
                            onChange={(e) => setBusiness_email(e.target.value)}
                            value={business_email}
                            rows='4'
                            type='text'
                            placeholder='Business Email'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_phone_number'
                            name='business_phone_number'
                            onChange={(e) =>
                              setBusiness_phone_number(e.target.value)
                            }
                            value={business_phone_number}
                            rows='4'
                            type='text'
                            placeholder='Business phone number'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_address'
                            name='business_address'
                            onChange={(e) =>
                              setBusiness__address(e.target.value)
                            }
                            value={business_address}
                            rows='4'
                            type='text'
                            placeholder='Business Address'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='full_name'
                            name='full_name'
                            onChange={(e) => setFull_name(e.target.value)}
                            value={full_name}
                            rows='4'
                            type='text'
                            placeholder='Full Name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='email'
                            name='email'
                            onChange={(e) => setEmail(e.target.value)}
                            value={email}
                            rows='4'
                            type='email'
                            placeholder='Email'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='phone_number'
                            name='phone_number'
                            onChange={(e) => setPhone_number(e.target.value)}
                            value={phone_number}
                            rows='4'
                            type='text'
                            placeholder='Phone Number'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='address'
                            name='address'
                            onChange={(e) => setAddress(e.target.value)}
                            value={address}
                            rows='4'
                            type='text'
                            placeholder='Address'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <textarea
                            id='additional_information_1'
                            name='additional_information_1'
                            onChange={(e) =>
                              setAdditional_information_1(e.target.value)
                            }
                            value={additional_information_1}
                            rows='4'
                            placeholder='Additional Information_1'
                            required
                          />
                        </div>
                        <div className='quick_service_input'>
                          <input
                            multiple
                            type='file'
                            onChange={fileSlectedHandler1}
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            multiple
                            type='file'
                            onChange={fileSlectedHandler2}
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            multiple
                            type='file'
                            onChange={fileSlectedHandler3}
                            required
                          />
                        </div>
                      </>
                    )}

                    {selectedCategory === "limited-liability-company" && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='full_name'
                            name='full_name'
                            onChange={(e) => setFull_name(e.target.value)}
                            value={full_name}
                            rows='4'
                            type='text'
                            placeholder='Full Name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='nature_of_business'
                            name='nature_of_business'
                            onChange={(e) =>
                              setNature_of_business(e.target.value)
                            }
                            value={nature_of_business}
                            rows='4'
                            type='text'
                            placeholder='Nature of Business'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_name'
                            name='business_name'
                            onChange={(e) => setBusiness_name(e.target.value)}
                            value={business_name}
                            rows='4'
                            type='text'
                            placeholder='Business Name'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_email'
                            name='business_email'
                            onChange={(e) => setBusiness_email(e.target.value)}
                            value={business_email}
                            rows='4'
                            type='email'
                            placeholder='Business Email'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_phone_number'
                            name='business_phone_number'
                            onChange={(e) =>
                              setBusiness_phone_number(e.target.value)
                            }
                            value={business_phone_number}
                            rows='4'
                            type='text'
                            placeholder='Business phone number'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='business_address'
                            name='business_address'
                            onChange={(e) =>
                              setBusiness__address(e.target.value)
                            }
                            value={business_address}
                            rows='4'
                            type='text'
                            placeholder='Business Address'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='slogan'
                            name='slogan'
                            onChange={(e) => setSlogan(e.target.value)}
                            value={slogan}
                            rows='4'
                            type='text'
                            placeholder='Slogan'
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
                            type='text'
                            placeholder='brand color'
                            required
                          />
                        </div>
                      </>
                    )}

                    {selectedCategory === "incorporated-trustees" && (
                      <>
                        <div className='quick_service_input'>
                          <input
                            id='nature_of_ngo'
                            name='nature_of_ngo'
                            onChange={(e) => setNature_of_ngo(e.target.value)}
                            value={nature_of_ngo}
                            rows='4'
                            placeholder='Nature of Ngo'
                            type='text'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='ngo_name'
                            name='ngo_name'
                            onChange={(e) => setNgo_name(e.target.value)}
                            value={ngo_name}
                            rows='4'
                            placeholder='Ngo Name'
                            type='text'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='ngo_email'
                            type='email'
                            name='ngo_email'
                            onChange={(e) => setNgo_email(e.target.value)}
                            value={ngo_email}
                            rows='4'
                            placeholder='Ngo Email'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='ngo_phone_number'
                            name='ngo_phone_number'
                            onChange={(e) =>
                              setNgo_phone_number(e.target.value)
                            }
                            value={ngo_phone_number}
                            rows='4'
                            type='email'
                            placeholder='Business Email'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='ngo_address'
                            name='ngo_address'
                            onChange={(e) => setNgo_address(e.target.value)}
                            value={ngo_address}
                            rows='4'
                            type='text'
                            placeholder='Ngo Address'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='full_name'
                            name='full_name'
                            onChange={(e) => setFull_name(e.target.value)}
                            value={full_name}
                            rows='4'
                            placeholder='Full Name'
                            type='text'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='phone_number'
                            name='phone_number'
                            onChange={(e) => setPhone_number(e.target.value)}
                            value={phone_number}
                            rows='4'
                            placeholder='Phone Number'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='email'
                            name='email'
                            onChange={(e) => setEmail(e.target.value)}
                            value={email}
                            rows='4'
                            placeholder='Email'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='phone_number'
                            name='phone_number'
                            onChange={(e) => setPhone_number(e.target.value)}
                            value={phone_number}
                            rows='4'
                            type='text'
                            placeholder='Phone Number'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <input
                            id='address'
                            name='address'
                            onChange={(e) => setAddress(e.target.value)}
                            value={address}
                            rows='4'
                            type='text'
                            placeholder='Address'
                            required
                          />
                        </div>

                        <div className='quick_service_input'>
                          <textarea
                            id='additional_information_1'
                            name='additional_information_1'
                            onChange={(e) =>
                              setAdditional_information_1(e.target.value)
                            }
                            value={additional_information_1}
                            rows='4'
                            placeholder='Additional Information_1'
                            required
                          />
                        </div>
                      </>
                    )}
                    <button type='submit'>Continue</button>
                  </div>
                </div>
              </form>
            </>
          )}
        </div>
        {/* <div className='exchanges_container'>
          <div className='exchanges_buttons'>
            <button
              onClick={() => setToggleExchange()}
              className={!toggleExchange ? `dark` : `light`}
            >
              Graphics Design
            </button>
            <button
              onClick={() => {
                setToggleExchange(true);
                setShowSelectedDetails(false);
              }}
              className={toggleExchange ? `dark` : `light`}
            >
              CAC Registration
            </button>
          </div>
          {!toggleExchange ? (
            <>
              {!showSelectedDetails ? (
                <div className='gift_card_exchange'>
                  <h4 className='instant_exchange'>Graphics Design</h4>
                  <p className='select_supplier'>
                    Select a category to get started.
                  </p>
                  <div className='choose__modalbtn'>
                    <button onClick={() => setShowBrandingModal(true)}>
                      Choose Category
                    </button>
                  </div>
                </div>
              ) : (
                <form onSubmit={handleSubmit}>
                  <div className='categories__details1'>
                    <div className='first__details'>
                      <div className='web_category_selection '>
                        <div className='choose__modalbtns'>
                          <img src={selectedCategoryImage} alt='' />
                          <button
                            className='choose__modal__btns'
                            onClick={() => {
                              // setShowWebPreviewModal(false);
                              setShowBrandingModal(true);
                            }}
                          >
                            Choose Category
                          </button>
                        </div>
                      </div>
                      <div className='features__wrapper'>
                        {allFeaturedata?.map((data) => (
                          <>
                            <p>{data.title}</p>
                            <div className='features__container'>
                              {data?.featurize_values?.map((item) => (
                                <div className='m-2'>
                                  {console.log("item", item)}
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
                        <h2>Addons</h2>
                        <p>
                          Select extra exciting fearures you would like to add
                          to your order.
                        </p>
                        {allAdons?.map((data) => (
                          <div className='single__addons'>
                            <div className='single__addons__label__input'>
                              <label>{data?.title}</label>
                              <input
                                type='checkbox'
                                name={data.slug}
                                value={data.id}
                                onChange={(e) => handleAdd_ons(e)}
                              />
                            </div>
                            <p className='single__desc'>{data?.description}</p>
                            <p className='single__price'>
                              {data?.formatted_price}
                            </p>
                          </div>
                        ))}
                      </div>
                      <div className='payments'>
                        <span className='payments__text'>You will pay</span>
                        <span className='payments__amount'>{price}</span>
                      </div>
                    </div>
                    <div className='second__details'>
                      <div className='quick_service_input'>
                        <input
                          id='email'
                          name='name'
                          type='tel'
                          onChange={(e) => setName(e.target.value)}
                          value={name}
                          placeholder='Business Name'
                          required
                        />
                      </div>

                      <div className='quick_service_input'>
                        <input
                          id='slogan'
                          name='slogan'
                          type='text'
                          value={slogan}
                          onChange={(e) => setSlogan(e.target.value)}
                          placeholder='Slogan'
                          required
                        />
                      </div>

                      <div className='quick_service_input'>
                        <textarea
                          id='description'
                          name='description'
                          onChange={(e) => setDescription(e.target.value)}
                          value={description}
                          placeholder='Describe your project'
                        />
                      </div>

                      <div className='quick_service_input'>
                        <input
                          id='colors'
                          name='colors'
                          type='text'
                          value={colors}
                          onChange={(e) => setColors(e.target.value)}
                          placeholder='Mobile Number'
                          required
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
                      {!spinner && <button type='submit'>Continue</button>}
                      {spinner && (
                        <button type='submit' disabled>
                          <FaSpinner /> Loading...
                        </button>
                      )}
                    </div>
                  </div>
                </form>
              )}
            </>
          ) : null}
        </div> */}

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

export default DashboardBranding;
