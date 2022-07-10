import React, { useCallback, useEffect, useState } from "react";
import Modal from "react-modal";
import "./index.scss";
import IMG from "../../../../assets/transaction-complete.svg";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import {
  getGiftCardSuccess,
  resetAllToFalse,
} from "../../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import IMG1 from "../../../../assets/ecard.svg";
import IMG2 from "../../../../assets/changes/print.png";
import { formatter } from '../../../../components/Helpers/index'
const customStyles = {
  content: {
    align: "center",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
    height: "700px", // <-- This sets the height
    width: "900px",
    overlfow: "scroll", // <-- This tells the modal to scrol
  },
};

const WebModal = ({
  data,
  closeGiftCardModal,
  handleCategorySelect,
  handleSelectedDetails,
  selectedCategory,
}) => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const [modalIsOpen, setIsOpen] = React.useState(false);
  // const dataArray = Object.entries(data).map(([, value]) => value);
  const [showClicked, setShowClicked] = useState("");

  // console.log("data", dataArray);
  function openModal() {
    setIsOpen(true);
  }

  function afterOpenModal() { }

  function closeModal() {
    dispatch(resetAllToFalse());
    dispatch(loadUser());
  }

  const showCardRates = (data) => {
    // setRates(data);
  };

  const handleSelect_rate = (e) => {
    // setGift_card_rates(e.target.value);
  };

  const fileSlectedHandler = (e) => {
    // setImage(e.target.files);
  };

  const handleSubmitGiftCard = (e) => {
    e.preventDefault();

    // closeGiftCardModal();
  };

  // console.log("fhahaja", data)

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
        <div className='modal__content'>
          <div className='logo__header'>
            <img src={IMG2} alt='img' />
          </div>

          <div className='title_header'>
            <h1>Choose a Category</h1>
            <p>Choose a category for your Branding Project</p>
          </div>
          <div className='row printing__category'>
            {data?.map((web, index) => (
              <div className="col-4 ">
                <div
                  className={`single__category_web ${showClicked === index && "button__picked"
                    }`}
                  key={index}
                >
                  <img src={web?.icon || web?.service_variant_icon} alt='img' />

                  <div className='design__price'>
                    <p> {!web?.formatted_price && "₦"} {web?.formatted_price || formatter.format(web?.price || 0)}</p>
                  </div>
                  <div className='choose__btn'>
                    <button
                      onClick={() => {
                        handleCategorySelect(web);
                        setShowClicked(index);
                        closeGiftCardModal();
                        handleSelectedDetails();
                      }}
                    >
                      {showClicked === index ? "Selected" : "Choose"}
                    </button>
                  </div>
                </div>
                <div className='variant_content'>
                  <p className='design__header'>{web?.title} </p>
                  <p className='design__description'>{web?.description}</p>
                </div>
              </div>
            ))}
          </div>

          {/* {Object.keys(selectedCategory).length > 0  &&
            <button
              onClick={() => {
                closeGiftCardModal();
                handleSelectedDetails();
              }}
            >
              Proceed
            </button>
          } */}
          {/* <button
              onClick={() => {
                closeGiftCardModal();
                // handleSelectedDetails();
              }}
            >
              Custom
            </button> */}
        </div>
      </Modal>
    </div>
  );
};

export default WebModal;
