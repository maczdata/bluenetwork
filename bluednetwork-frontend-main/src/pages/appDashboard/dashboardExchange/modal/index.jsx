import React, { useEffect } from "react";
import Modal from "react-modal";
import "./index.scss";
import IMG from "../../../../assets/transaction-complete.svg";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import { resetAllToFalse } from "../../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import { Link } from "react-router-dom";

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
  // .ReactModal__Overlay .ReactModal__Overlay--after-open {
  //   background-color: rgba(0, 0, 0, 0.7) !important;
  // }
};

const SuccessOrFailModal = ({ data, datas }) => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const [modalIsOpen, setIsOpen] = React.useState(false);

  useEffect(() => {
    // setIsOpen(localStorage.getItem('hideModal')
    // );
  }, []);

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

          {/* <div className='service_terms'>
            congratulations
          </div> */}
          <div className='modal_content'>
            {data?.data?.status === true ? (
              <h1 className='pass'>Congratulations</h1>
            ) : data?.data?.status === false ? (
              <h1 className='fail'>Failed</h1>
            ) : (
              // !data?.message && (<h1 className='fail'>Failed</h1>)
              ""
            )}
            <p>
              {data?.message ||
                (datas && (
                  <>
                    <h1 className='fail'>Failed</h1> <p>{datas}</p>{" "}
                  </>
                ))}
              <br />
              {datas == "Phone number is required" && (
                <Link
                  className='text-primary'
                  to='/app/account_setting'
                  onClick={closeModal}
                >
                  Update phone Number
                </Link>
              )}
            </p>
          </div>
        </div>
      </Modal>
    </div>
  );
};

export default SuccessOrFailModal;
