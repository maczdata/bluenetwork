import React from "react";
import { useFormik } from "formik";
import Modal from "react-modal";
import "./index.scss";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import { useDispatch, useSelector } from "react-redux";
import { getAllBanks } from "../../../../redux/actions/service";
import IMG4 from "../../../../assets/plus.svg";
import {
  loadUser,
  updateAccountPassword,
} from "../../../../redux/actions/auth";
import { Spinner } from "reactstrap";
import { useState } from "react";

const customStyles = {
  content: {
    align: "center",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
    width: "30%",
  },
};

const UpdatePassword = () => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const spinner = auth?.loading;
  const [validate, setValidate] = useState(false);

  // let subtitle;
  const [modalIsOpen, setIsOpen] = React.useState(false);

  function openModal() {
    setIsOpen(true);
  }

  function afterOpenModal() {
    dispatch(getAllBanks());
  }

  function closeModal() {
    setIsOpen(false);
    dispatch(loadUser());
  }

  const formik = useFormik({
    initialValues: {
      old_password: "",
      password: "",
      password_confirmation: "",
    },
    onSubmit: (values) => {
      if (values.password !== values.password_confirmation) {
        setValidate(true);
        setTimeout(() => setValidate(false), 2000);
      } else {
        dispatch(updateAccountPassword(values));
        setTimeout(() => setIsOpen(false), 1000);
      }
    },
  });

  return (
    <div className='exchange__modal'>
      {/* <button onClick={openModal}>Open Modal</button> */}
      <img src={IMG4} alt='' onClick={openModal} />
      <Modal
        isOpen={modalIsOpen}
        onAfterOpen={afterOpenModal}
        onRequestClose={closeModal}
        style={customStyles}
        contentLabel='Example Modal'
        ariaHideApp={false}
      >
        <div className='acc_modal_header'>
          <h1>Update Password</h1>
          {/* <button onClick={closeModal}>close</button> */}
          <img
            src={CloseModalIcon}
            alt='close modal'
            onClick={closeModal}
            className='close_modal'
          />
        </div>
        <div className='welcome_servic'>
          <form onSubmit={formik.handleSubmit}>
            <div>
              <label>Enter Old Password</label>
              <input
                id='old_password'
                name='old_password'
                type='password'
                onChange={formik.handleChange}
                value={formik.values.old_password}
                placeholder='Old Password'
                required
              />
            </div>

            <div>
              <label>Enter New Password</label>
              <input
                id='password'
                name='password'
                type='password'
                onChange={formik.handleChange}
                value={formik.values.password}
                placeholder='Password'
                required
              />
            </div>

            <div>
              <label>Password Confirmation</label>
              <input
                id='password_confirmation'
                name='password_confirmation'
                type='password'
                onChange={formik.handleChange}
                value={formik.values.password_confirmation}
                placeholder='Password Confirmation'
                required
              />
            </div>
            {validate && "password don't match"}
            {spinner ? (
              <button type='submit' disabled>
                <Spinner color='light' />
              </button>
            ) : (
              <button type='submit'>Update</button>
            )}
          </form>
        </div>
      </Modal>
    </div>
  );
};

export default UpdatePassword;
