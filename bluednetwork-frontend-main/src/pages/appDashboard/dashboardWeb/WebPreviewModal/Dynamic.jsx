import React, { useEffect } from "react";
import Modal from "react-modal";
import "./index.scss";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import { addOns, resetAllToFalse } from "../../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import { FaSpinner } from "react-icons/fa";
import FlutterWaveBranding from "../../dashboardBranding/flutterWave/FlutterWaveBranding";
import Dummy from "../../../../assets/changes/dummy.png";
import Paystack from "../../../../components/paystack/Paystack";
import TopUpWallet from "../../dashboardHome/TopUpWallet";
import { formatter } from "../../../../components/Helpers";

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
  loading = false,
  fields,
  addons,
  selectedCategory,
  amount_to_pay
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
  // const amount_to_pay = servicesData?.previewSuccess?.amount_to_pay;

  useEffect(() => {
    dispatch(loadUser());
  }, [showWalletButton]);

  const data = servicesData.previewSuccess;;
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

  
  
  const getValueParam = (param)=>{
    if(typeof param === "object"){
      return param?.name
    } else {
      return param
    }
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
            <img src={selectedCategory.icon || selectedCategory?.service_variant_icon} alt='' className='w-100 img-fluid' />
            <p className='preview__image__text d-flex justify-content-between'><span>{selectedCategory?.title}</span> <span>₦{formatter.format(selectedCategory?.price || 0)}</span></p>
            {/* <p className='preview__image__text'></p> */}
          </div>
          <div className='meta__details d-flex justify-content-between'>
            {/* <div className='single__meta'>
              <h2>{data?.Package && "Package"}</h2>
              <p>{data?.Package}</p>
            </div> */}
            {Object.entries(fields).map((entry, i) => (
              <div className='single__meta' key={entry[0]}>
                <h2 style={{ textTransform: 'capitalize' }}>{entry[0]?.replace(/_/g, " ")}</h2>
                <p>{getValueParam(entry[1])}</p>
              </div>

            ))}
          </div>

          {addons?.length > 0 && <h2>Addons</h2>}
          {addons?.length > 0 && addons.map((addon) => (
            <div className='addons' key={addon.id}>
              <div className='addons__details'>
                <h2 style={{ textTransform: 'capitalize' }}>{addon.title}</h2>
                <p>{formatter.format(addon.price)}</p>
              </div>
            </div>
          ))}

          <div className='edit__action'>
            <div className='edit__btn'>
              <button onClick={closePreviewModal}>edit</button>
            </div>
            <div className='total__amount'>
              <p>
                {/* Total <span>₦{data?.amount_to_pay}</span> */}
                Total <span>₦{amount_to_pay}</span>
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
