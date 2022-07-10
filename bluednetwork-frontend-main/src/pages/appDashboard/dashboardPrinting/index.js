import React, { useEffect, useState } from "react";
import "./index.scss";
import Banner from "../../../assets/Frame 1.png";
import IMG1 from "../../../assets/cropped-shot-african.svg";
import IMG2 from "../../../assets/wallet 2.svg";
import { useDispatch, useSelector } from "react-redux";
import RecentTransactions from "../../../components/RecentTransactions";
import WalletBallance from "../dashboardHome/WalletBallance";
import PrintingModal from "./PrintingModal";
import { Spinner } from "reactstrap";
import {
  addOns,
  brandingFeature,
  getPreview,
  getPrintingPayment,
} from "../../../redux/actions/service";
import PrintPreviewModal from "./PrintPreviewModal";
import SuccessOrFailModal from "../dashboardExchange/modal";
import Fade from "../../../components/advert";

const DashboardPrinting = () => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const auth = useSelector((state) => state.auth);
  const bal = auth?.data?.wallet_balance;
  const data = servicesData?.previewSuccess?.amount_to_pay;
  const bdPrinting = servicesData?.services?.responesFive?.data?.data;
  const price = servicesData?.addOns?.data?.formatted_price;

  useEffect(() => {}, [bdPrinting]);

  const allFeaturedata = servicesData?.featureSuccess?.data;
  const allAdons = servicesData?.addOns?.data?.addons;
  const successOrFail = servicesData?.printingPaymentSuccess;
  const spinner = servicesData.loading;

  const [showPrintingModal, setShowPrintingModal] = useState(false);
  const [showSelectedDetails, setShowSelectedDetails] = useState(false);
  const [showPreviewModal, setShowPreviewModal] = useState(false);
  const [selectedCategory, setCSelectedCategory] = useState("");

  const [quantity, setQuantity] = useState("");
  const [number_of_pages, setNumber_of_pages] = useState("");
  const [description, setDescription] = useState("");
  const [additional_information, setAdditional_information] = useState("");
  const [image, setImage] = useState(null);
  const is_preview = true;
  const [serviceType, setServiceType] = useState("bd_printing");
  const [type, setType] = useState([]);
  const [add_ons, setAdd_ons] = useState([]);
  const [finalInfo, setFinalInfo] = useState({});
  const [categoryKey, setCategoryKey] = useState("");
  const [no_pages, setNo_pages] = useState("");
  const [showWalletButton, setShowWalletButton] = useState(false);
  const [selectedCategoryImage, setSelectedCategoryImage] = useState("");

  const closeGiftCardModal = () => {
    setShowPrintingModal(false);
  };

  const closePreviewModal = () => {
    setShowPreviewModal(false);
  };

  const fileSlectedHandler = (e) => {
    setImage(e.target.files);
  };

  const handleSelectedDetails = () => {
    setShowSelectedDetails(true);
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

  const handleCategorySelect = (print) => {
    setCategoryKey(print.key);
    setCSelectedCategory(print.id);
    dispatch(addOns(print.key));
    setNo_pages(print.key);
    dispatch(brandingFeature(print.id));
    setSelectedCategoryImage(print.icon)
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    let images = [...image];
    let formData = new FormData();
    images.forEach((image, i) => formData.append(`design_document[]`, image));

    dispatch(
      getPreview({
        quantity,
        number_of_pages,
        description,
        additional_information,
        is_preview,
        serviceType,
        "design_document[]": formData,
        service_key: categoryKey,
        add_ons,
        type,
      })
    );

    setFinalInfo({
      quantity,
      number_of_pages,
      description,
      additional_information,
      is_preview,
      serviceType,
      "design_document[]": formData,
      service_key: categoryKey,
      add_ons,
      type,
    });

    // setShowPreviewModal(true);
  };

  const handlePaymentSuccess = () => {
    let images = [...image];
    let formData = new FormData();
    images.forEach((image, i) => formData.append(`design_document[]`, image));

    if (bal < data) {
      alert("insufficient balance");
      setShowWalletButton(true);
    } else {
      dispatch(
        getPrintingPayment({
          quantity,
          number_of_pages,
          description,
          additional_information,
          is_preview,
          serviceType,
          "design_document[]": formData,
          service_key: categoryKey,
          add_ons,
          type,
        })
      );

      setTimeout(() => {
        setShowPreviewModal(false);
      }, 1000);
    }
  };

  return (
    <div>
      {servicesData?.isprintingPaymentSuccess && (
        <SuccessOrFailModal data={successOrFail} />
      )}

      {showPrintingModal && (
        <PrintingModal
          data={bdPrinting}
          closeGiftCardModal={closeGiftCardModal}
          handleCategorySelect={handleCategorySelect}
          handleSelectedDetails={handleSelectedDetails}
          selectedCategory={selectedCategory}
        />
      )}

      {showPreviewModal && (
        <PrintPreviewModal
          handlePaymentSuccess={handlePaymentSuccess}
          closePreviewModal={closePreviewModal}
          showWalletButton={showWalletButton}
        />
      )}

      <div className='barner__container mt-3'>
        <Fade />
      </div>
      <div className='display_on_small_screen_printing'>
        <WalletBallance />
      </div>

      <div className='first__section'>
        <div className='printing_container'>
          <h4 className='header'>Printing</h4>
          {!showSelectedDetails ? (
            <div className='printing_category_selection'>
              <h5 className='printing_header'>Printing</h5>
              <p className='select_printing_supplier'>
                Select a category to get started.
              </p>
              <div className='choose__modalbtn'>
                <button onClick={() => setShowPrintingModal(true)}>
                  Choose Category
                </button>
              </div>
            </div>
          ) : (
            <form onSubmit={handleSubmit}>
              <div className='categories__details fix-on-small-screen'>
                <div className='first__details'>

                  <div className='print_category_selection '>
                    <div className='choose__modalbtns'>
                      <img src={selectedCategoryImage} alt='' />
                      <button
                        className='choose__modal__btns'
                        onClick={() => {
                          // setShowWebPreviewModal(false);
                          setShowPrintingModal(true);
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
                            <div className='style_radio_label'>
                              <label className=''>{item?.title}</label>
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
                      Select extra exciting fearures you would like to add to
                      your order.
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
                        <p className='single__price'>{data?.formatted_price}</p>
                      </div>
                    ))}
                  </div>

                  <div className='payments'>
                    <span className='payments__text'>You will pay</span>
                    <span className='payments__amount'>{price}</span>
                  </div>
                </div>
                <div className='second__details'>
                  {no_pages == "documents" || no_pages == "Typesetting" ? (
                    <div className='quick_service_input'>
                      <input
                        id='email'
                        name='number_of_pages'
                        type='tel'
                        onChange={(e) => setNumber_of_pages(e.target.value)}
                        value={number_of_pages}
                        placeholder='Number of pages'
                        required
                      />
                    </div>
                  ) : (
                    <div className='quick_service_input'>
                      <input
                        id='email'
                        name='quantity'
                        type='tel'
                        onChange={(e) => setQuantity(e.target.value)}
                        value={quantity}
                        placeholder='Quantity'
                        required
                      />
                    </div>
                  )}

                  <div className='quick_service_input'>
                    <textarea
                      id='description'
                      name='description'
                      onChange={(e) => setDescription(e.target.value)}
                      value={description}
                      rows='4'
                      placeholder='Describe your project'
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
                  {!spinner && <button type='submit'>Continue</button>}
                  {spinner && (
                    <button type='submit' disabled>
                      <Spinner /> Loading...
                    </button>
                  )}
                </div>
              </div>
            </form>
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

export default DashboardPrinting;
