import React, { useEffect, useState } from "react";
import Modal from "react-modal";
import "./index.scss";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import {
  airtimeExchange,
  resetAllToFalse,
} from "../../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import { Spinner } from "reactstrap";
import { toast } from "react-toastify";

const customStyles = {
  content: {
    align: "center",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
    overlfow: "scroll",
  },
};

const ConfirmModal = ({ data, closeAirtimeModal, setPhoneData, phoneData, loading, amountToReceive, reloadFields }) => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const servicesData = useSelector((state) => state.service);
  const spinner = servicesData.loading;
  
  const [modalIsOpen, setIsOpen] = React.useState(false);
  const [image, setImage] = useState(null);
  const [showUpload, setShowUpload] = useState(false);
  const userInfo = auth?.data?.first_name;

  useEffect(() => {
    dispatch(loadUser());
  }, []);

  useEffect(()=>{
    servicesData?.isAirtimeFailed && alert(servicesData?.isAirtimeFailed)
  }, [servicesData?.isAirtimeFailed])
  function openModal() {
    // setIsOpen(true);
  }

  function afterOpenModal() { }

  function closeModal() {
    dispatch(resetAllToFalse());
    dispatch(loadUser());
  }

  const fileSlectedHandler = (e) => {
    setImage(e.target.files[0]);
  };

  const handleSubmitAirtime = (e) => {
    e.preventDefault();
    let formData = new FormData();
    let keysArray = Object.keys(data)
    let valuesArray = Object.values(data)
    formData.append("proof_document", image);
    for (let i = 0; i < keysArray.length; i++) {
      formData.append(`custom_fields[${keysArray[i]}]`, valuesArray[i])
    }
    // formData.append(
    //   "custom_fields[airtime_for_cash_network]",
    //   data.airtime_for_cash_network
    // );
    // formData.append(
    //   "custom_fields[airtime_amount_transferred]",
    //   data.airtime_amount_transferred
    // );
    // formData.append(
    //   "custom_fields[airtime_for_cash_sender_phone_number]",
    //   data.airtime_for_cash_sender_phone_number
    // );
    dispatch(airtimeExchange(formData));
    setPhoneData()
    setTimeout(() => {
      closeAirtimeModal();
      if (reloadFields) {
        reloadFields()
      }
      setPhoneData()
      dispatch(loadUser())
    }, 1000);
  };
console.log(servicesData?.isAirtimeFailed)
  return (
    <div className='exchange__modal'>
      <Modal
        isOpen={!modalIsOpen}
        onAfterOpen={afterOpenModal}
        onRequestClose={closeModal}
        style={customStyles}
        contentLabel='Example Modal'
        ariaHideApp={false}
      >
        <div className='exchange_navigation'>
          {showUpload && (
            <button onClick={() => setShowUpload(false)}>Back</button>
          )}
          <img
            src={CloseModalIcon}
            alt='close modal'
            onClick={closeAirtimeModal}
            className='close_modal'
          />
        </div>

        <form onSubmit={handleSubmitAirtime}>
          {!showUpload && (
            <div className='form_container text-center'>
              <div>
                <h1>Hello {userInfo}</h1>
                <p>
                  transfer N{data.airtime_amount_transferred} worth of airtime
                  to <br /> this phone number below:
                </p>
              </div>
              <div className='number__to__send'>
                <h2>{phoneData?.meta?.receiving_number}</h2>
                <p>(Please never save this number)</p>
                <p>WE DO NOT ACCEPT VTU OR RECHARGE PIN</p>
                <p>charges: {phoneData?.meta?.percentage_taken}%</p>
                {amountToReceive && <p>You will receive : {amountToReceive}</p>}
              </div>

              <div className='warning__notice'>
                <p>
                  PLEASE USE THE DISPLAY PHONE NUMBER ONLY ONCE BECAUSE <br /> A
                  NEW NUMBER WILL BE PROVIDED FOR EVERY TRANSACTION <br />
                  SO AS TO AVOID LOSS OF AIRTIME
                </p>
              </div>

              <button onClick={() => setShowUpload(true)}>Continue</button>
            </div>
          )}

          {showUpload && (
            <div className="upload__details">
              <h1>Upload Proof of Transaction</h1>
              <div className='file_upload'>
                <input type='file' onChange={fileSlectedHandler} />
              </div>
              {/* <button type='submit' disabled={image === null}>Upload</button> */}
              {spinner ? (
                <button type='submit' disabled>
                  <Spinner color='light' />
                </button>
              ) : (
                <button type='submit' disabled={image === null}>Upload</button>
              )}
            </div>
          )}
        </form>
      </Modal>
    </div>
  );
};

export default ConfirmModal;
