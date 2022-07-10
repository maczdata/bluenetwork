import React, { useEffect, useState } from "react";
import "./index.scss";
import IMG2 from "../../../assets/wallet 2.svg";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../redux/actions/auth";
import FlutterWave from "./flutterWave/FlutterWave";
import IMG5 from "../../../assets/Arrow 2 (1).svg";
import IMG4 from "../../../assets/Arrow 2.svg";
import {
  allTransaction,
  getAllBanks,
  singleTransactions,
  updataBankData,
} from "../../../redux/actions/service";
import EmptyTransactionHistory from "../../../components/emptyTransactionHistory";
import TopUpWallet from "../bill/TopUpWallet";
import AddAccountModal from "../dashboardAccountSetting/modal";
import { useFormik } from "formik";
import Modal from "react-modal";
import { Spinner } from "reactstrap";
import CloseModalIcon from "../../../assets/closeModalIcon.svg";
import axios from "axios";
import { toast } from "react-toastify";
import useGetAcctName from "../dashboardAccountSetting/modal/hook/useGetAcctName";
import rewriteUrl from "../../../helper/rewriteUrl";


const Wallet = () => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const { data } = auth;
  const amount = auth?.data;
  const servicesData = useSelector((state) => state.service);
  const { allTransactions } = servicesData;
  console.log('amt', amount);
  useEffect(() => {
    // dispatch(allTransaction({ per_page: 10, page: 1 }));
  }, []);

  useEffect(() => {
    dispatch(loadUser());
  }, []);

  const [showWalletForm, setShowWalletForm] = useState(false);

  const displayWalletForm = () => {
    setShowWalletForm(!showWalletForm);
  };

  const [popupAddAccountModal, setPopupAccountModal] = useState()

  const [res, setRes] = useState()

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

  const [withdrawalPinModal, setWithdrawalPinModal] = useState(false)

  const handleSetWithdrawalPin = async (value) => {
    let response
    response = await axios(
      {
        method: 'post',
        url: `${rewriteUrl()}front_api/account/withdrawal-pin`,
        params: {
          token: token,
          withdrawal_pin: value.withdrawal_pin
        }
      }
    ).then((ressy)=>{
      setRes(ressy)
      toast.success(ressy.data.message)
      setWithdrawalPinModal(false)
      setWalletDebit(true)
    }).catch((error)=>{
      setRes()
      toast.error(error.response?.data?.message)
    })
  }

  const [walletDebit, setWalletDebit] = useState()

  const handleWalletDebit = async (value) =>{
    let response
    response = await axios(
      {
        method: 'post',
        url: `${rewriteUrl()}front_api/account/wallet/debit`,
        params: {
          amount: value.amount,
          token: token,
          withdrawal_pin: value.withdrawal_pin
        }
      }
    ).then((ressy)=>{
      setRes(ressy)
      toast.success(ressy.data.message)
      setWalletDebit(false)
    }).catch((error)=>{
      setRes()
      toast.error(error.response.data.message)
    })
  }
  const handleWithdrawal = () =>{
    if (amount?.banking_data?.bank_name === ''){
      console.log('condition met');
      handleGetOtp()
      setPopupAccountModal(true)
      setIsOpen(true)
    }

    else if(amount?.withdrawal_pin_set === 'unverified'){
      setPopupAccountModal(false)
      setWithdrawalPinModal(true)
    }

    else{
      setWalletDebit(true)
    }
  }
  const token = localStorage.getItem('access_token')
  const [fundType, setFundType] = useState()
  
  const datay = servicesData?.allBanks?.data;

  const spinner = servicesData?.loading;

  const [modalIsOpen, setIsOpen] = React.useState(true);

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

  function afterOpenModal() {
    dispatch(getAllBanks());
  }

  function closeModal() {
    setIsOpen(false);
    setWithdrawalPinModal(false)
    setWalletDebit(false)
    dispatch(loadUser());
  }

  const {acctName, getAcctName, setAcctName} = useGetAcctName()


  const formik = useFormik({
    initialValues: {
      bank_id: "",
      account_name: "",
      account_number: "",
      otp: "",
      token: token
    },
    onSubmit: (values) => {
      dispatch(updataBankData({...values, account_name: acctName?.data.accountName}));
      setTimeout(() => {

        setIsOpen(false)
        setPopupAccountModal(false)
      }, 1000);
    dispatch(loadUser());

    },
  });
  const formiks = useFormik({
    initialValues: {
      
      withdrawal_pin: "",
      token: token
    },
    onSubmit: (values) => {
      setRes('loading')
      handleSetWithdrawalPin(values)
      setTimeout(() => setIsOpen(false), 1000);
    dispatch(loadUser());

    },
  });

  const formiksy = useFormik({
    initialValues: {
      amount: '',
      withdrawal_pin: "",
      token: token
    },
    onSubmit: (values) => {
      setRes('loading')
      handleWalletDebit(values)
      setTimeout(() => setIsOpen(false), 1000);
    dispatch(loadUser());

    },
  });
  console.log('wall', walletDebit);
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
  return (
    <div className='wallet__dashboard'>
      <div className='row'>
        {
          popupAddAccountModal && <Modal
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
                  {datay &&
                    datay?.map((provider, id) => (
                      <option key={id} value={provider.id}>
                        {provider.name}
                      </option>
                    ))}
                </select>
              </div>
              <div>
                <input
                  id='account_name'
                  name='account_name'
                  type='text'
                  value={acctName? acctName.data.accountName: ''}
                  placeholder='Account Name'
                  disabled
                />
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
        }
        {
          withdrawalPinModal && !popupAddAccountModal &&<Modal
          isOpen={withdrawalPinModal}
          onRequestClose={closeModal}
          style={customStyles}
          contentLabel='Example Modal'
          ariaHideApp={false}
        >
          <div className='acc_modal_header'>
            <h1>Create Withdrawal Pin</h1>
            {/* <button onClick={closeModal}>close</button> */}
            <img
              src={CloseModalIcon}
              alt='close modal'
              onClick={closeModal}
              className='close_modal'
            />
          </div>
          <div className='welcome_servic'>
            <form onSubmit={formiks.handleSubmit}>
              <div>
                <input
                  id='withdrawal_pin'
                  name='withdrawal_pin'
                  type='tel'
                  onChange={formiks.handleChange}
                  value={formiks.values.withdrawal_pin}
                  placeholder='Withdrawal Pin'
                  required
                />
              </div>
              
              {res === 'loading' ? (
                <button type='submit' disabled>
                  <Spinner color='light' />
                </button>
              ) : (
                <button type='submit'>Update Pin</button>
              )}
            </form>
          </div>
        </Modal>
        }
        {
          walletDebit && <Modal
          isOpen={walletDebit}
          onRequestClose={closeModal}
          style={customStyles}
          contentLabel='Example Modal'
          ariaHideApp={false}
        >
          <div className='acc_modal_header'>
            <h1>Withdraw funds</h1>
            {/* <button onClick={closeModal}>close</button> */}
            <img
              src={CloseModalIcon}
              alt='close modal'
              onClick={closeModal}
              className='close_modal'
            />
          </div>
          <div className='welcome_servic'>
            <form onSubmit={formiksy.handleSubmit}>
              <div>
                <input
                  id='amount'
                  name='amount'
                  type='tel'
                  onChange={formiksy.handleChange}
                  value={formiksy.values.amount}
                  placeholder='Amount'
                  required
                />
              </div>
              <div>
                <input
                  id='withdrawal_pin'
                  name='withdrawal_pin'
                  type='tel'
                  onChange={formiksy.handleChange}
                  value={formiksy.values.withdrawal_pin}
                  placeholder='Withdrawal Pin'
                  required
                />
              </div>
              
              {res === 'loading' ? (
                <button type='submit' disabled>
                  <Spinner color='light' />
                </button>
              ) : (
                <button type='submit'>Create withdrawal request</button>
              )}
            </form>
          </div>
        </Modal>
        }
        <div className='col-md-5 mb-4'>
          <div className='col-md-12'>
            <div className='wallet__balance__wrapper'>
              <div className='wallet__title'>
                <span>
                  <img src={IMG2} alt='' />
                </span>
                <p>Wallet Balance</p>
              </div>
              <div className='wallet__balance'>
                <span className='wallet__amount'>
                  {amount?.formatted_wallet_balance}
                </span>
              </div>

              <div className='wallet__action__btn '>
                <div className='third d-flex wallet__input'>
                  {!showWalletForm ? (
                    <>
                      <button onClick={displayWalletForm} style={{marginRight: '5px'}}>Top Up Wallet</button>
                      <button className='btn-withdraw' onClick={()=>{
                        handleWithdrawal()
                      }}>Withdraw Funds</button>
                    </>
                  ) : (
                    !fundType &&<div className="topup_options">
                      <h6>Topup wallet</h6>
                      <ul>
                        {/* <li onClick={()=>{
                          setFundType('card')
                        }}>
                          Fund with card
                        </li> */}
                        <li onClick={()=>{
                          setFundType('bank')
                        }}>
                          Bank transfer
                        </li>
                      </ul>
                    </div>
                    // <TopUpWallet displayWalletForm={displayWalletForm} />
                  )}
                  {
                    fundType === 'bank' && showWalletForm && <div className="details">
                      <p>
                        Money sent to this account will automatically credit your wallet
                      </p>
                      <div>
                        <h5>
                          Account number
                        </h5>
                        <div>
                          <p>
                            {amount?.virtual_account?.data?.accountNumber}
                          </p>
                        </div>
                      </div>
                      <div>
                        <h5>
                          Bank name
                        </h5>
                        <div>
                          <p>
                            {amount?.virtual_account?.data?.bankName}
                          </p>
                        </div>
                      </div>
                      <div>
                        <h5>
                          Account name
                        </h5>
                        <div>
                          <p>
                            {amount?.virtual_account?.data?.customerName}
                          </p>
                        </div>
                      </div>
                      <div>
                        <span onClick={()=> {
                          setFundType()
                          displayWalletForm()
                          }}>
                          Back
                        </span>
                      </div>
                    </div>
                  }
                </div>
                {/* <FlutterWave /> */}
                {/* <button className='btn-withdraw'>Withdraw Funds</button> */}
              </div>
            </div>
          </div>
          <div className='col-md-12'>{/* <RecentTransactions /> */}</div>
        </div>
        <div className='col-md-4 seconds'>
          <div className='wallet__transaction__header'>
            <span>
              <img src={IMG2} alt='' />
            </span>
            <p>Recent Transactions</p>
          </div>
          {allTransactions[1]?.meta?.pagination?.total ? (
            Object.entries(allTransactions[1])
              .filter(([key]) => key !== "meta")
              .map(([key, value], i) => (
                <>
                  <div
                    key={value.id}
                    className='transaction'
                    onClick={() => dispatch(singleTransactions(value.id))}
                  >
                    <div

                    // className={data.status === "Completed" ? `img-symbol` : `img-symbol-2`}
                    >
                      {/* {console.log("color", value)} */}
                      {value.type === "incoming" ? (
                        <div className='img-symbol-2'>
                          <img src={IMG5} alt='' />
                        </div>
                      ) : (
                        <div className='img-symbol'>
                          <img src={IMG4} alt='' />
                        </div>
                      )}
                    </div>

                    <div className='content'>
                      {Object.entries(value).map(([key, values], i) => (
                        <>
                          <div className='details'>
                            <p className='header'>{values?.main_message}</p>
                            <p className='amount-2'>{values?.sub_message}</p>
                          </div>
                        </>
                      ))}

                      <div className='status'>
                        <p className='provider'>{value.amount_formatted}</p>
                        <p className='dates'>
                          <span // className={data.status === "Completed" ? "completed" : "pending"}
                            className='completed'
                          ></span>
                          {value.updated_at.split(" ")[0]}
                        </p>
                      </div>
                    </div>
                  </div>
                </>
              ))
          ) : (
            <EmptyTransactionHistory />
          )}
        </div>
      </div>
    </div>
  );
};

export default Wallet;
