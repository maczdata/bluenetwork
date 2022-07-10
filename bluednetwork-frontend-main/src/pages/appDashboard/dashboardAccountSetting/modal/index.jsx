import React, { useEffect, useState } from "react";
import { useFormik } from "formik";
import Modal from "react-modal";
import "./index.scss";
import CloseModalIcon from "../../../../assets/closeModalIcon.svg";
import { useDispatch, useSelector } from "react-redux";
import { getAllBanks, updataBankData } from "../../../../redux/actions/service";
import IMG4 from "../../../../assets/plus.svg";
import { loadUser } from "../../../../redux/actions/auth";
import { Spinner } from "reactstrap";
import axios from "axios";
import useGetAcctName from "./hook/useGetAcctName";
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
    width: "30%",
  },
};

const AddAccountModal = () => {
  const token = localStorage.getItem('access_token')

  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);

  const data = servicesData?.allBanks?.data;
  const spinner = servicesData?.loading;

  // let subtitle;
  const [modalIsOpen, setIsOpen] = React.useState(false);

  function openModal() {
    setIsOpen(true);
    handleGetOtp()
  }

  function afterOpenModal() {
    dispatch(getAllBanks());
  }

  function closeModal() {
    setIsOpen(false);
    dispatch(loadUser());
  }

  const {acctName, getAcctName, setAcctName} = useGetAcctName()

  const formik = useFormik({
    initialValues: {
      bank_id: "",
      account_name: acctName?.data.accountName,
      account_number: "",
      otp: "",
      token: token
    },
    onSubmit: (values) => {
      dispatch(updataBankData({...values, account_name: acctName?.data.accountName}));
      setTimeout(() => setIsOpen(false), 1000);
      dispatch(loadUser());
    },
  });

  const handleGetOtp = async () =>{
    let response
    response = await axios(
      {
        method: 'get',
        url: `${rewriteUrl()}front_api/account/generate_bank_otp`,
        params: {
          token: token
        }
      }
    )
  }
  const auth = useSelector((state) => state.auth);

  const amount = auth?.data;


  const [loading, setLoading] = useState()

  useEffect(()=>{
    if(formik.values.account_number.length === 10){
      console.log('triggered');
      let data = {
        bank_id: formik.values.bank_id,
        account_number: formik.values.account_number
      }
      getAcctName(data)
      setLoading(true)
    }
  }, [formik.values.account_number])

  useEffect(()=>{
    if(formik.values.account_number.length < 10){
      console.log('triggereyyy');
      setAcctName()
      setLoading(false)
    }
    setAcctName()
  }, [formik.values.account_number, formik.values.bank_id])
  console.log(acctName);
  return (
    <div className='exchange__modal'>
      {/* <button onClick={openModal}>Open Modal</button> */}
      {amount?.banking_data?.bank_name === '' && <img src={IMG4} alt='' onClick={openModal} />}
      <Modal
        isOpen={modalIsOpen}
        onAfterOpen={afterOpenModal}
        onRequestClose={closeModal}
        style={customStyles}
        contentLabel='Example Modal'
        ariaHideApp={false}
      >
        <div className='acc_modal_header'>
          <h1>Add New Bank Account</h1>
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
              <select
                name='bank_id'
                id='bank_id'
                className='mb-3'
                value={formik.values.bank_id}
                onChange={formik.handleChange}
                required
              >
                <option value=''>Select Bank</option>
                {data &&
                  data?.map((provider, id) => (
                    <option key={id} value={provider.id}>
                      {provider.name}
                    </option>
                  ))}
              </select>
            </div>
            <div className="spin_contain">
              <input
                id='account_name'
                name='account_name'
                type='text'
                value={acctName? acctName.data.accountName: ''}
                placeholder='Account Name'
                disabled
                required
              />
              {!acctName && loading && <Spinner className="spin" color="light"></Spinner>}
            </div>
            <div>
              <input
                id='account_number'
                name='account_number'
                type='tel'
                onChange={formik.handleChange}
                value={formik.values.account_number}
                placeholder='Account Number'
                required
              />
            </div>
            <div>
              <input
                id='otp'
                name='otp'
                type='otp'
                onChange={formik.handleChange}
                value={formik.values.otp}
                placeholder='Please enter Email OTP'
                required
              />
            </div>
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

export default AddAccountModal;
