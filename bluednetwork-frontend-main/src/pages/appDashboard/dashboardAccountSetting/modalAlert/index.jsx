import React, { useEffect } from "react";
import Modal from "react-modal";
import "./index.scss";
import IMG from "../../../../assets/transaction-complete.svg";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import { Link } from "react-router-dom";
import { resetAllToFalse } from "../../../../redux/actions/auth";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";

const customStyles = {
  content: {
    align: "center",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
  },
};

const SuccessOrFailModal = ({ data, datas }) => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  console.log("auth", auth);
  const [modalIsOpen, setIsOpen] = React.useState(false);

  useEffect(() => {}, []);

  function openModal() {
    // setIsOpen(true);
  }

  function afterOpenModal() {
    // references are now sync'd and can be accessed.
  }

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
          onClick={closeModal}
          className='close_modal'
        />
        <div className='welcome_service'>
          <img src={IMG} alt='welcome image' />

          <div className='modal_content'>
            <p>
              {data?.message === "Profile data updated" ||
              data?.message === "Banking detail updated" ||
              datas ? (
                <div className='modal_content'>
                  <h1 className='pass'>Congratulations</h1>
                  <p>{data?.message || datas}</p>
                </div>
              ) : (
                <div className='modal_content'>
                  <h1 className='fail'>Failed</h1>
                  <p>{data?.message}</p>
                </div>
              )}
            </p>
          </div>
        </div>
      </Modal>
    </div>
  );
};

export default SuccessOrFailModal;
