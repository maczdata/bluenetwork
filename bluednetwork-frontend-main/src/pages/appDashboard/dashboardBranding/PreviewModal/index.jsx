import React, { useEffect } from "react";
import Modal from "react-modal";
import "./index.scss";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import { resetAllToFalse } from "../../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import { FaSpinner } from "react-icons/fa";
import FlutterWaveBranding from "../flutterWave/FlutterWaveBranding";

const customStyles = {
  content: {
    // align: "center",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
    height: "550px",
    width: "35%",
    overlfow: "scroll",
  },
};

const PreviewModal = ({
  closeGiftCardModal,
  handlePaymentSuccess,
  showWalletButton,
}) => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const [modalIsOpen, setIsOpen] = React.useState(false);

  const auth = useSelector((state) => state.auth);
  const bal = auth?.data?.wallet_balance;
  const amount_to_pay = servicesData?.previewSuccess?.amount_to_pay;

  useEffect(() => {
    dispatch(loadUser());
  }, [showWalletButton]);

  const data = servicesData.previewSuccess;
  const addons = data?.addons;
  const spinner = servicesData.loading;
  console.log("previewSuccess", data);
  function openModal() {
    setIsOpen(true);
  }

  function afterOpenModal() {}

  function closeModal() {
    dispatch(resetAllToFalse());
    dispatch(loadUser());
  }

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
        <div className='preview__modal__content'>
          <h1>Order Summary</h1>
          <div className='meta__details'>
            <div className='single__meta'>
              <h2>{data?.Business && "Brand Name"}</h2>
              <p>{data?.Business}</p>
            </div>
            <div className='single__meta'>
              <h2>{data?.slogan && "Slogan"}</h2>
              <p>{data?.slogan}</p>
            </div>
            <div className='single__meta'>
              <h2>{data?.["Brand Colors"] && "Brand Colors"}</h2>
              <p>{data?.["Brand Colors"]}</p>
            </div>
            <div className='single__meta'>
              <h2>{data?.Type && "Type"}</h2>
              <p>{data?.Type}</p>
            </div>
            <div className='single__meta'>
              <h2>{data?.Size && "Size"}</h2>
              <p>{data?.Size}</p>
            </div>
            <div className='single__meta'>
              <h2>{data?.Package && "Package"}</h2>
              <p>{data?.Package}</p>
            </div>
          </div>
          <div className='descriptions'>
            <h2>{data?.Description && "Description"}</h2>
            <p>{data?.Description}</p>
          </div>
          <div className='addons'>
            <h2>Addons</h2>
            {addons &&
              Object.entries(addons).map(([, value]) => (
                <div className='addons__details'>
                  <p>{value}</p>
                </div>
              ))}
          </div>
          <div className='edit__action'>
            <div className='edit__btn'>
              <button onClick={closeGiftCardModal}>edit</button>
            </div>
            <div className='total__amount'>
              <p>
                Total <span>{data?.amount_to_pay}</span>
              </p>
            </div>
          </div>
          <div className='call__to__action'>
            <button>Add to Cart</button>

            {!spinner && (
              <>
                <button onClick={handlePaymentSuccess}>Pay Now</button>
              </>
            )}
            {spinner && (
              <>
                <button type='submit' disabled>
                  <FaSpinner /> Loading...
                </button>
              </>
            )}
          </div>
          <div className='recharge__wallet'>
            {bal < amount_to_pay ? <span>Insufficient wallet balance</span> : null}
            {bal < amount_to_pay ? <FlutterWaveBranding /> : null}
          </div>
        </div>
      </Modal>
    </div>
  );
};

export default PreviewModal;
