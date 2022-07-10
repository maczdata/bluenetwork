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
import Paystack from "../../../../components/paystack/Paystack";
import TopUpWallet from "../../dashboardHome/TopUpWallet";

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

const WebPreviewModal = ({
  closePreviewModal,
  handlePaymentSuccess,
  showWalletButton,
  loading = false
}) => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const [modalIsOpen, setIsOpen] = React.useState(false);

  const [formData, SetFormData] = React.useState({ amount: 0 });

  const reloadAmount = () => {
    SetFormData({ amount: 0 })
  }

  const auth = useSelector((state) => state.auth);
  const bal = auth?.data?.wallet_balance;
  const amount_to_pay = servicesData?.previewSuccess?.amount_to_pay;

  useEffect(() => {
    dispatch(loadUser());
  }, [showWalletButton]);

  const data = servicesData.previewSuccess;
  const addons = data?.addons;
  const spinner = servicesData.loading;
  // const listInfo = data?.variant_description.split("");
  // console.log("listInfo", listInfo);

  function openModal() {
    setIsOpen(true);
  }

  function afterOpenModal() { }

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
          <div className='preview__image'>
            <img src={data?.variant_icon} alt='' className='img-fluid' />
            <p className='preview__image__text'>{data?.Package}</p>
          </div>
          <div className='descriptions mt-3'>
            {data?.variant_description && (
              <div className='single__meta'>
                <p>{data?.variant_description}</p>
              </div>
            )}
          </div>
          <div className='meta__details'>
            {/* <div className='single__meta'>
              <h2>{data?.Package && "Package"}</h2>
              <p>{data?.Package}</p>
            </div> */}
            {data?.website_title && (
              <div className='single__meta'>
                <h2>{data?.website_title && "Website Title"}</h2>
                <p>{data?.website_title}</p>
              </div>
            )}
            {data?.brand_color && (
              <div className='single__meta'>
                <h2>{data?.brand_color && "Brand Color"}</h2>
                <p>{data?.brand_color}</p>
              </div>
            )}
            {data?.content_goals && (
              <div className='single__meta'>
                <h2>{data?.content_goals && "Content Goals"}</h2>
                <p>{data?.content_goals}</p>
              </div>
            )}
            {data?.domain_name && (
              <div className='single__meta'>
                <h2>{data?.domain_name && "Domain Name"}</h2>
                <p>{data?.domain_name}</p>
              </div>
            )}
            {data?.post_title && (
              <div className='single__meta'>
                <h2>{data?.post_title && "Post Title"}</h2>
                <p>{data?.post_title}</p>
              </div>
            )}
          </div>

          <div className='descriptions mt-3'>
            <h2>{data?.description && "Description"}</h2>
            <p>{data?.description}</p>
          </div>

          {addons?.length == 0 ? (
            <div className='addons'>
              <h2>Addons</h2>
              {addons &&
                Object.entries(addons).map(([, value]) => (
                  <div className='addons__details'>
                    <p>{value}</p>
                  </div>
                ))}
            </div>
          ) : null}

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

            {(bal > amount_to_pay) && 
            <> {(!spinner && !loading) && (
              <>
                <button onClick={handlePaymentSuccess}>Pay Now</button>
              </>
            )}
              {(spinner || loading) && (
                <>
                  <button type='submit' disabled>
                    <FaSpinner /> Loading...
                  </button>
                </>
              )}</>}
          </div>
          <div className='recharge__wallet'>
            {bal < amount_to_pay ? (
              <span>Insufficient wallet balance</span>
            ) : null}
          </div>
          {/* {bal < amount_to_pay ? <FlutterWaveBranding /> : null} */}
          {bal < amount_to_pay ? <TopUpWallet info={formData} reloadAmount={reloadAmount} className="wallet__btn" /> : null}
        </div>
      </Modal>
    </div>
  );
};

export default WebPreviewModal;
