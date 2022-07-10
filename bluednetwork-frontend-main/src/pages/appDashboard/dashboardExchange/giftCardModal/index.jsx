import React, { useCallback, useEffect, useState } from "react";
import Modal from "react-modal";
import "./index.scss";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import {
  getGiftCardCurrencyRates,
  getGiftCardSuccess,
  GIFT_CARD_FAIL,
  GIFT_CARD_SUCCESS,
  LOADING,
  resetAllToFalse,
} from "../../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import IMG1 from "../../../../assets/ecard.svg";
import { Spinner } from "reactstrap";
import axios from "axios";
import rewriteUrl from "../../../../helper/rewriteUrl";

const customStyles = {
  content: {
    align: "center",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
    maxHeight: "600px",
    overlfow: "scroll",
  },
};

const GiftCardmodal = ({ data, closeGiftCardModal, loading }) => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const servicesData = useSelector((state) => state.service);
  const spinner = servicesData.loading;
  const [modalIsOpen, setIsOpen] = React.useState(false);

  const gift_card_id = data?.id;
  const [gift_card_currency_id, setGift_card_currency_id] = useState([]);
  const [image, setImage] = useState(null);
  const [gift_card_rates, setGift_card_rates] = useState([]);

  const currency = data?.currencies;
  const [showCards, setShowCards] = useState(false);

  const [showTypes, setShowTypes] = useState(false);
  const [showTypesCategoryId, setShowTypesCategoryId] = useState("");
  const [gift_card_sub_category_id, setGift_card_sub_category_id] =
    useState("");

  const [showreceiptEcode, setShowreceiptEcode] = useState(false);
  const [showreceiptPhysicalCard, setShowreceiptPhysicalCard] = useState(false);

  const ratesDataEcode = servicesData?.giftCardsCurrencyRates?.data;
  const ratesDataPhysicalCard = servicesData?.giftCardsCurrencyRates?.data;
  const [rates, setRates] = useState("");
  const [value, setValue] = useState("0");
  const [cardInfo, setCardInfo] = useState([]);
  const [showUpload, setShowUpload] = useState(false);
  const [showClicked, setShowClicked] = useState("");
  const [showClicked2, setShowClicked2] = useState("");
  const [zero, setZero] = useState("0");

  const [fields, setFields] = useState({});

  const receipts =
    data?.categories &&
    Object.entries(data?.categories).map(
      ([key, value], i) =>
        value?.category?.children.length != 0 && value?.category?.children
    );

  const [inputList, setInputList] = useState([]);
  const [codes, setCodes] = useState({});

  // handle input change
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    let nameSplit = name.split("-");
    const idRate = nameSplit[0];
    const index = nameSplit[1];
    setFields({
      ...fields,
      [idRate]: {
        ...fields[idRate],
        [index]: value,
      },
    });
  };

  // handle click event of the Remove button
  const handleRemoveClick = (index) => {
    const list = [...inputList];
    list.splice(index, 1);
    setInputList(list);
  };

  // handle click event of the Add button
  const handleAddClick = () => {
    setInputList([...inputList, { code: "" }]);
  };

  const codeKeys = Object.keys(codes);

  const getrateLabel = (name, value) => {
    setCodes({ ...codes, [value]: codes[value] ? codes[value] + 1 : 1 });
  };

  const removerateLabel = (name, value) => {
    setCodes({ ...codes, [value]: codes[value] ? codes[value] - 1 : 0 });
  };

  const increase = useCallback(
    (rateId, rateValue) => {
      const productInCart = cardInfo.find((item) => item.rate_id === rateId);
      let card_info;
      if (productInCart) {
        card_info = cardInfo.map((item) =>
          item.rate_id === rateId
            ? { ...item, rateValue: rateValue, quantity: item.quantity + 1 }
            : { ...item }
        );
        setCardInfo(card_info);
      } else {
        card_info = [
          ...cardInfo,
          { rateValue: rateValue, rate_id: rateId, quantity: 1 },
        ];
        setCardInfo(card_info);
      }
      getTotal(card_info);
      getrateLabel(rateId, rateValue);
      handleAddClick();
    },
    [cardInfo, setCardInfo]
  );

  const decrease = (rateId, rateValue) => {
    const productInCart = cardInfo.find((item) => item.rate_id === rateId);
    let card_info;
    if (productInCart) {
      card_info = cardInfo.map((item) =>
        item.rate_id === rateId
          ? {
              ...item,
              rateValue: rateValue,
              quantity: item.quantity - 1 < 0 ? 0 : item.quantity - 1,
            }
          : { ...item }
      );
      setCardInfo(card_info);
    } else {
      card_info = [
        ...cardInfo,
        { rateValue: rateValue, rate_id: rateId, quantity: 1 },
      ];
      setCardInfo(card_info);
    }
    getTotal(card_info);
    removerateLabel(rateId, rateValue);
    handleRemoveClick();
  };

  const getTotal = (cardInfo) => {
    const mappedValue = cardInfo.map((data) => {
      return data.rateValue * data.quantity;
    });
    const totalPrice =
      mappedValue.length > 1
        ? mappedValue.reduce((a, b) => a + b)
        : mappedValue[0];
    setValue(totalPrice);
  };

  useEffect(() => {}, [cardInfo, value]);

  function openModal() {
    // setIsOpen(true);
  }

  function afterOpenModal() {}

  function closeModal() {
    dispatch(resetAllToFalse());
    dispatch(loadUser());
  }

  const handleCountrySelect = (e) => {
    setShowTypes(true);
    setGift_card_currency_id(e.target.value);
  };

  const showCardRates = (data) => {
    setRates(data);
  };

  const fileSlectedHandler = (e) => {
    setImage(e.target.files);
  };

  const handleSubmitEcode = async (e) => {
    e.preventDefault();
    const eCodeInfor = cardInfo.map((item) => {
      return {
        rate_id: item.rate_id,
        quantity: item.quantity,
        codes: Object.values(fields[item.rateValue]),
      };
    });

    const token = localStorage.getItem("access_token");
    dispatch({
      type: LOADING,
    });
    const config = {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    };

    try {
      const res = await axios.post(
        `${rewriteUrl()}front_api/service/order/exchange/giftcard`,
        {
          gift_card_id,
          gift_card_currency_id,
          gift_card_category_id: showTypesCategoryId,
          gift_card_rates: eCodeInfor,
        },
        config
      );
      dispatch({
        type: GIFT_CARD_SUCCESS,
        payload: res?.data,
      });
      setTimeout(() => {
        closeGiftCardModal();
      }, 1000);
      console.log("GIFT_CARD_SUCCESS", res?.data);
    } catch (error) {
      console.log("GIFT_CARD_FAIL", error.message);
      dispatch({
        type: GIFT_CARD_FAIL,
      });
      return error;
    }
  };

  const handleSubmitGiftCard = (e) => {
    e.preventDefault();

    let images = [...image];
    let formData = new FormData();
    formData.append("gift_card_id", gift_card_id);
    formData.append("gift_card_currency_id", gift_card_currency_id);
    formData.append("gift_card_sub_category_id", gift_card_sub_category_id);
    formData.append("gift_card_category_id", showTypesCategoryId);

    images.forEach((image, i) =>
      formData.append(`gift_card_proof_files[]`, image)
    );

    cardInfo.forEach(
      (card, i) =>
        (formData.append(`gift_card_rates[${i}][rate_id]`, card.rate_id),
      formData.append(`gift_card_rates[${i}][quantity]`, card.quantity))
    );
    // formData.append("gift_card_rates", JSON.stringify(cardInfo));

    dispatch(getGiftCardSuccess(formData));
    setTimeout(() => {
      closeGiftCardModal();
    }, 1000);
  };

  return (
    <div className='exchange__modal'>
      {/* <button onClick={openModal}>Open Modal</button> */}
      <Modal
        isOpen={!modalIsOpen}
        onAfterOpen={afterOpenModal}
        onRequestClose={closeModal}
        style={customStyles}
        contentLabel='Example Modal'
        ariaHideApp={false}
      >
        <img
          src={CloseModalIcon}
          alt='close modal'
          onClick={closeGiftCardModal}
          className='close_modal'
        />
        <form onSubmit={handleSubmitGiftCard}>
          {!showUpload && (
            <div className='welcome_service'>
              <img src={data?.gift_card_image} alt='welcome image' />
              <div className='modal_content'>
                <p>{data?.title}</p>
              </div>
              <div className='quick_service_input'>
                <select
                  name='gift_card_currency_id'
                  id='cars'
                  className='mb-3'
                  value={gift_card_currency_id}
                  onChange={(e) => handleCountrySelect(e)}
                  required
                >
                  <option>Select Country/Currency</option>
                  {currency &&
                    currency.map((currency, id) => (
                      <option key={id} value={currency.id}>
                        {currency?.name}
                      </option>
                    ))}
                </select>
                <div className='card_form_wrapper'>
                  <h6>Select a card type</h6>
                  <div className='card__types'>
                    {showTypes
                      ? data?.categories &&
                        Object.entries(data?.categories).map(
                          ([key, value, index], i) => (
                            <div
                              key={key}
                              className={`single_card ${
                                showClicked === i && "single_card_selected"
                              }`}
                              onClick={() => {
                                // setShowCards(true);
                                setShowClicked(i);
                                setShowTypesCategoryId(value.id);
                                if (
                                  value?.category?.title === "Physical Card"
                                ) {
                                  setShowCards(true);
                                  setShowreceiptEcode(false);
                                } else if (
                                  value?.category?.title === "E code"
                                ) {
                                  dispatch(
                                    getGiftCardCurrencyRates({
                                      gift_card_id,
                                      gift_card_currency_id,
                                      gift_card_category_id: value.id,
                                    })
                                  );
                                  setShowCards(false);
                                  setShowreceiptPhysicalCard(false);
                                  setShowreceiptEcode(true);
                                }
                              }}
                            >
                              <h5>{value?.category?.title}</h5>
                            </div>
                          )
                        )
                      : null}
                  </div>

                  <div className='card_form'>
                    {showCards &&
                      receipts?.map((card, index) =>
                        Object.entries(card).map(([key, value, index], i) => (
                          <>
                            <div
                              key={i}
                              className={`single_card ${
                                showClicked2 === i && "single_card_selected"
                              }`}
                              onClick={() => {
                                showCardRates(card.currency_rates);
                                setGift_card_sub_category_id(value.id);
                                setShowClicked2(i);
                                dispatch(
                                  getGiftCardCurrencyRates({
                                    gift_card_id,
                                    gift_card_currency_id,
                                    gift_card_category_id: showTypesCategoryId,
                                  })
                                );
                                setShowreceiptPhysicalCard(true);
                              }}
                            >
                              <img src={IMG1} alt='img' />
                              <p>{value?.title}</p>
                            </div>
                          </>
                        ))
                      )}
                  </div>
                </div>

                {showreceiptPhysicalCard && (
                  <div className='card_form_wrapper'>
                    <h6>Card Value</h6>
                    <div className='card_form2'>
                      {ratesDataPhysicalCard &&
                        ratesDataPhysicalCard.map((card) => (
                          <>
                            <div className='single_card_value'>
                              <div className='card_rate_value'>
                                ₦{card.rate_value}
                              </div>
                              <div className='quantity_wrapper'>
                                <div
                                  className='increase'
                                  onClick={() =>
                                    decrease(card.id, card.rate_value)
                                  }
                                >
                                  -
                                </div>
                                <div className='quantity_amount'>
                                  {cardInfo.length == []
                                    ? 0
                                    : cardInfo &&
                                      cardInfo.map(
                                        (data) =>
                                          data.rate_id === card.id &&
                                          data.quantity
                                        // : null
                                      )}
                                </div>
                                <div
                                  className='increase'
                                  onClick={() => {
                                    increase(card.id, card.rate_value);
                                    setZero(null);
                                  }}
                                >
                                  +
                                </div>
                              </div>
                            </div>
                          </>
                        ))}
                    </div>
                    <div className='total'>
                      <p>
                        You will receive <span>₦{value}</span>
                      </p>
                    </div>
                  </div>
                )}
              </div>

              {showreceiptPhysicalCard && (
                <button
                  className='mt-2'
                  onClick={() => setShowUpload(true)}
                  disabled={cardInfo.length == []}
                >
                  Proceed
                </button>
              )}
            </div>
          )}
          {showUpload && (
            <>
              <div className='welcome_service image_upload'>
                {/* <h2>Upload Your Card</h2>
                <p>Upload your gift cards images and receipts.</p> */}
                {/* <input type='file' onChange={(e) => fileSlectedHandler(e)} /> */}
                <div className='form-group'>
                  <div className='d-flex'>
                    <div className='d-flex'>
                      <div className='file-uploader-mask d-flex justify-content-center align-items-center'>
                        {image &&
                          Object.entries(image).map(([key, value], i) => (
                            <p>{value.name}</p>
                          ))}
                        {console.log("checking", image)}
                      </div>
                      <input
                        multiple
                        className='file-input'
                        type='file'
                        onChange={fileSlectedHandler}
                      />
                    </div>
                  </div>
                </div>
                {/* {image == null ? <button className='mt-4 disabled' disabled>Proceed</button> : <button type='submit' className='mt-4'>
                  Proceed
                </button>} */}

                <div className='upload__btn'>
                  {spinner ? (
                    <button type='submit' className='mt-4' disabled>
                      <Spinner color='light' />
                    </button>
                  ) : (
                    <button
                      type='submit'
                      className='mt-4'
                      disabled={image === null}
                    >
                      Upload
                    </button>
                  )}
                </div>
              </div>
            </>
          )}

          {showreceiptEcode && (
            <div className='card_form_wrapper text-center mt-5'>
              <h6>Card Value</h6>
              <div className='card_form2'>
                {ratesDataEcode &&
                  ratesDataEcode.map((card) => (
                    <>
                      <div className='single_card_value'>
                        <div className='card_rate_value'>
                          ₦{card.rate_value}
                        </div>
                        <div className='quantity_wrapper'>
                          <div
                            className='increase'
                            onClick={() => decrease(card.id, card.rate_value)}
                          >
                            -
                          </div>
                          <div className='quantity_amount'>
                            {cardInfo.length == []
                              ? 0
                              : cardInfo &&
                                cardInfo.map(
                                  (data) =>
                                    data.rate_id === card.id && data.quantity
                                  // : null
                                )}
                          </div>
                          <div
                            className='increase'
                            onClick={() => {
                              increase(card.id, card.rate_value);
                              setZero(null);
                            }}
                          >
                            +
                          </div>
                        </div>
                      </div>
                    </>
                  ))}
              </div>
              <div className='total'>
                <p>
                  You will receive <span>₦{value}</span>
                </p>
              </div>

              <div className='inserted__input__wrapper'>
                {codeKeys &&
                  codeKeys.map((data) => (
                    <div className='inserted__input__container'>
                      {codes[data] > 0 && <label>{data}</label>}
                      <div className='inserted__input'>
                        {Array.apply(0, Array(codes[data])).map(function (
                          x,
                          i
                        ) {
                          // console.log({ data, x, i }, fields[data]);
                          return (
                            <input
                            placeholder="Enter code here"
                              onChange={(e) => handleInputChange(e)}
                              name={`${data}-${i}`}
                              defaultValue={
                                fields && fields[data] && fields[data][i]
                                  ? fields[data][i]
                                  : ""
                              }
                              key={i}
                            />
                          );
                        })}
                      </div>
                    </div>
                  ))}
              </div>
              {codeKeys && (
                // <button type='submit' onClick={(e) => handleSubmitEcode(e)}>
                //   Proceed
                // </button>
                <div className='upload__btn text-center'>
                  {spinner ? (
                    <button
                      type='submit'
                      className='mt-4'
                      disabled={cardInfo.length == []}
                    >
                      <Spinner color='light' />
                    </button>
                  ) : (
                    <button
                      type='submit'
                      className='mt-4'
                      onClick={(e) => handleSubmitEcode(e)}
                      disabled={cardInfo.length == []}
                    >
                      Proceed
                    </button>
                  )}
                </div>
              )}
            </div>
          )}
        </form>
      </Modal>
    </div>
  );
};

export default GiftCardmodal;
