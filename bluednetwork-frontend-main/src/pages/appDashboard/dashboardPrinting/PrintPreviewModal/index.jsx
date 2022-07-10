import React, { useEffect } from "react";
import Modal from "react-modal";
import "./index.scss";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import { resetAllToFalse } from "../../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import { FaSpinner } from "react-icons/fa";
import FlutterWaveBranding from "../../dashboardBranding/flutterWave/FlutterWaveBranding";
import Dummy from "../../../../assets/changes/dummy.png";


const customStyles = {
  content: {
    // align: "center",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
    height: "700px",
    width: "400px",
    overlfow: "scroll",
  },
};

const PrintPreviewModal = ({
  closePreviewModal,
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
          onClick={closePreviewModal}
          className='close_modal'
        />
        <div className='preview__modal__content'>
          <h1>Order Summary</h1>
          <div className="preview__image">
            <img src={Dummy} alt="" className="img-fluid"/>
            <p className="preview__image__text">{data?.Package}</p>
          </div>
          <div className='meta__details'>
            <div className='single__meta'>
              <h2>{data?.Package && "Package"}</h2>
              <p>{data?.Package}</p>
            </div>
            {data?.quantity && (
              <div className='single__meta'>
                <h2>{data?.quantity && "Quantity"}</h2>
                <p>{data?.quantity}</p>
              </div>
            )}
            {data?.number_of_pages && (
              <div className='single__meta'>
                <h2>{data?.number_of_pages && "Number of pages"}</h2>
                <p>{data?.number_of_pages}</p>
              </div>
            )}
            {data?.[`Flier Types`] && (
              <div className='single__meta'>
                <h2>{data?.[`Flier Types`] && "Type"}</h2>
                <p>{data?.[`Flier Types`]}</p>
              </div>
            )}
            {data?.[`Banner Types`] && (
              <div className='single__meta'>
                <h2>{data?.[`Banner Types`] && "Type"}</h2>
                <p>{data?.[`Banner Types`]}</p>
              </div>
            )}
            {data?.[`Card Type`] && (
              <div className='single__meta'>
                <h2>{data?.[`Card Type`] && "Type"}</h2>
                <p>{data?.[`Card Type`]}</p>
              </div>
            )}
            {data?.[`Color Type`] && (
              <div className='single__meta'>
                <h2>{data?.[`Color Type`] && "Type"}</h2>
                <p>{data?.[`Color Type`]}</p>
              </div>
            )}
            {data?.Size && (
              <div className='single__meta'>
                <h2>{data?.Size && "Size"}</h2>
                <p>{data?.Size}</p>
              </div>
            )}
            {data?.Sizes && (
              <div className='single__meta'>
                <h2>{data?.Sizes && "Sizes"}</h2>
                <p>{data?.Sizes}</p>
              </div>
            )}
            {data?.Orientation && (
              <div className='single__meta'>
                <h2>{data?.Orientation && "Orientation"}</h2>
                <p>{data?.Orientation}</p>
              </div>
            )}
          </div>
          <div className='descriptions mt-3'>
            <h2>{data?.description && "Description"}</h2>
            <p>{data?.description}</p>
          </div>
          {addons?.length != 0  ? (
            <div className='addons'>
              <h2>Addons</h2>
              {addons &&
                Object.entries(addons).map(([, value]) => (
                  <div className='addons__details'>
                    <p>{value}</p>
                  </div>
                ))}
            </div>
          ): null}
          <div className='edit__action'>
            <div className='edit__btn'>
              <button onClick={closePreviewModal}>edit</button>
            </div>
            <div className='total__amount'>
              <p>
                Total <span>₦{data?.amount_to_pay}</span>
              </p>
            </div>
          </div>
          <div className='call__to__action'>
            {/* <button>Add to Cart</button> */}

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
            {bal < amount_to_pay ? (
              <span>Insufficient wallet balance</span>
            ) : null}
            {bal < amount_to_pay ? <FlutterWaveBranding /> : null}
          </div>
        </div>
      </Modal>
    </div>
  );
};

export default PrintPreviewModal;
