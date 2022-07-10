import React, { useEffect, useState } from "react";
import "./dashboardHome.scss";
import Banner from "../../../assets/Frame 1.png";
import IMG1 from "../../../assets/cropped-shot-african.svg";
import IMG2 from "../../../assets/wallet 2.svg";
import { useDispatch, useSelector } from "react-redux";
import {
  getAllServices,
  getVariant,
  quickSerice,
} from "../../../redux/actions/service";
import RecentTransactions from "../../../components/RecentTransactions";
import WalletBallance from "./WalletBallance";
import SuccessOrFailModal from "../dashboardExchange/modal";
import { FaSpinner } from "react-icons/fa";
import { loadUser } from "../../../redux/actions/auth";
import { Modal, Spinner } from "reactstrap";
import { Link } from "react-router-dom";
import Fade from "../../../components/advert";
import rewriteUrl from "../../../helper/rewriteUrl";
import axios from "axios";
import { toast } from "react-toastify";
import CloseModalIcon from "../../../assets/closeModalIcon.svg";

const DashboardHome = () => {
  useEffect(() => {
    dispatch(getAllServices());
    dispatch(loadUser());
  }, []);

  // get service data from state
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const spinner = servicesData.loading;

  const successOrFail = servicesData?.transaction;
  const fail = servicesData?.payload;
  // console.log("checking", {successOrFail, fail, servicesData});

  //
  const bdExchange = servicesData?.responseOne?.data?.data;
  const bdBills = servicesData.responseTwo?.data?.data;
  const bdWeb = servicesData?.responesThree?.data?.data;
  const bdBranding = servicesData.responesFour?.data?.data;
  const bdPrinting = servicesData.responesFive?.data?.data;
  const variantData = servicesData?.variant?.data;

  //
  const [providers, setProviders] = useState([]);
  // save service key to a state
  const [serviceKey, setServiceKey] = useState("airtime-topup");

  // for airtime-topup
  const [network, setNetwork] = useState("");
  const [phone_number, setPhone_number] = useState("");
  const [amount, setAmount] = useState("");

  // for data-subscription
  const [network_1, setNetwork_1] = useState("");
  const [phone_number_1, setPhone_number_1] = useState("");
  const [variant, setVariant] = useState("");

  // for cable tv
  const [cable_tv_service, setCable_tv_service] = useState("");
  const [cable_tv_package, setCable_tv_package] = useState("");
  const [cable_tv_smart_card_number, setCable_tv_smart_card_number] =
    useState("");

  // for electricity
  const [electricity_disco, setElectricity_disco] = useState("");
  const [electricity_amount, setElectricity_amount] = useState("");
  const [electricity_meter_number, setElectricity_meter_number] = useState("");
  const [electricity_meter_type, setElectricity_meter_type] = useState("");

  const auth = useSelector((state) => state.auth);
  const bal = auth?.data?.wallet_balance;

  // handleChange to get service key
  const getServiceKey = (e) => {
    const value = e.target.value;
    setServiceKey(value);
    const filteredprovider = bdBills.filter((bill) => {
      if (bill.key === value) {
        return bill.children;
      }
    });
    return setProviders(filteredprovider[0].children);
  };

  const [electricity_disco_id, setElectricity_disco_id] = useState()

  const [networkKey, setNetworkKey] = useState()

  useEffect(()=>{
    if(electricity_meter_number && electricity_meter_number.length >= 13) {
      setRes('loading')
      handlePreview()
    }
    else if (cable_tv_smart_card_number.length >= 10) {
      setRes('loading')
      handlePreview()
    }
  }, [electricity_meter_number, cable_tv_smart_card_number])

  const handleAirtimeCharge = () => {
    dispatch(quickSerice({ serviceKey, network, amount, phone_number, networkValue, setModalState }));
  };

  const handleDataCharge = () => {
    dispatch(quickSerice({ serviceKey, network_1, variant, phone_number_1, networkKey, setModalState }));
  };

  const handleCableCharge = () => {
    dispatch(
      quickSerice({
        serviceKey,
        cable_tv_service,
        cable_tv_package,
        cable_tv_smart_card_number,
        setModalState
      })
    );
  };

  const handleElectricityCharge = () => {
    dispatch(
      quickSerice({
        serviceKey,
        electricity_disco,
        electricity_amount,
        electricity_meter_number,
        electricity_meter_type,
        electricity_disco_id,
        setModalState
      })
    );
  };

  const getKey = (e) => {
    const value = e.target.value;
    const name = e.target.name;
    if (name === "network_1") {
      setNetwork_1(value);
    }

    if (name === "cable_tv") {
      setCable_tv_service(value);
      setError(false)
    }

    if (name === "electricity") {
      setElectricity_disco(value);
      setError(false)
    }

    const filteredprovider = providers.find((data) => {
      if (data.key == value) {
        return data.key;
      }
    });
    const key = filteredprovider?.key;
    dispatch(getVariant({ key }));
  };

  // submit function to api
  const handleSubmit = (e) => {
    e.preventDefault();
    if (serviceKey === "airtime-topup") {
      handleAirtimeCharge();
      setNetwork("");
      setPhone_number("");
      setAmount("");
    } else if (serviceKey === "data-subscription") {
      handleDataCharge();
      setNetwork_1("");
      setPhone_number_1("");
      setVariant("");
    } else if (serviceKey === "cable_tv") {
      handleCableCharge();
      setCable_tv_service("");
      setCable_tv_package("");
      setCable_tv_smart_card_number("");
    } else if (serviceKey === "electricity") {
      handleElectricityCharge();
      setElectricity_disco("");
      setElectricity_amount("");
      setElectricity_meter_number("");
      // setElectricity_meter_type("");
    }
  };
  const handlePreview = async () => {
    console.log('STARTED');
    const url = `${rewriteUrl()}front_api/service/order/preview2`
    const token = localStorage.getItem('access_token')
    if (serviceKey === "airtime-topup") {
      
      // handleAirtimeCharge();
      // setNetwork("");
      // setPhone_number("");
      // setAmount("");
    } else if (serviceKey === "data-subscription") {
      // handleDataCharge();
      // setNetwork_1("");
      // setPhone_number_1("");
      // setVariant("");
    } else if (serviceKey === "cable_tv") {
      let response
      response = await axios(
        {
          url: url,
          method: 'post',
          params: {
            service_key: cable_tv_service,
            billerNo: cable_tv_smart_card_number,
            token: token
          }
        }
      ).then((res)=>{
        console.log(res.data, 'ressttttt');
        setRes(res.data)
      }).catch((err)=>{
        console.log(err.data, 'resterrrrr');
        setErr(err.response.data.message)
        toast.error(err.response.data.message)
      })
      // handleCableCharge();
      // setCable_tv_service("");
      // setCable_tv_package("");
      // setCable_tv_smart_card_number("");
    } else if (serviceKey === "electricity") {
      let response
      response = await axios(
        {
          url: url,
          method: 'post',
          params: {
            service_key: electricity_disco,
            billerNo: electricity_meter_number,
            token: token,
            type: electricity_meter_type
          }
        }
      ).then((res)=>{
        console.log(res.data, 'ressttttt');
        setRes(res.data)
      }).catch((err)=>{
        console.log(err, 'resterrrrr');
        setErr(err.response.data.message)
        toast.error(err.response.data.message)
      })
      // handleElectricityCharge();
      // setElectricity_disco("");
      // setElectricity_amount("");
      // setElectricity_meter_number("");
      // setElectricity_meter_type("");
    } 
  }

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
      padding: 10
    },
  };

  const closeModal = () => {
    setModalState()
    dispatch(loadUser())
  }

  const [error, setError] = useState()

  const [err, setErr] = useState()

  const [res, setRes] = useState()

  const [modalState, setModalState] = useState()

  const [networkValue, setNetworkValue] = useState()

  const [idx, setIdx] = useState()

  console.log('serviceMine', servicesData)

  return (
    <div>
      {servicesData?.isQuickService ? (
        <SuccessOrFailModal data={successOrFail} />
      ) : (
        servicesData?.isError && <SuccessOrFailModal datas={fail} />
      )}

      <div className='barner__container mt-3'>
        <Fade />
      </div>
      <div className='first__section'>
        <div className='wallet__information display_on_small_screen'>
          <WalletBallance />
        </div>
        <div className='quick__service'>
          <div className='quick__service_form'>
            <h2>Quick Service</h2>
            <form onSubmit={(e)=>{
              e.preventDefault()
              // setPreview('loading')
              if(serviceKey === 'airtime-topup' || serviceKey === 'data-subscription'){
                handleSubmit(e)
              }
              else !res && res === 'loading' && handlePreview()
              }}>
              <div className='quick_service_input'>
                <select
                  name='service_key'
                  id='company'
                  className='mb-3'
                  onChange={getServiceKey}
                  defaultValue={serviceKey.service_key}
                  required
                >
                  <option value=''>Select Service</option>
                  {bdBills &&
                    bdBills.map((bills, id) => (
                      <option key={id} value={bills.key}  onClick={()=>{
                        setIdx(id)
                        setError()
                        setRes()
                        setErr()
                      }}>
                        {bills.title}
                      </option>
                    ))}
                </select>
              </div>

              {serviceKey === "airtime-topup" ? (
                <>
                  <div className='quick_service_input'>
                    <select
                      name='network'
                      id='cars'
                      className='mb-3'
                      value={network}
                      onChange={(e) => {
                        setNetwork(e.target.value);
                        // console.log(e.target.value);
                      }}
                      required
                    >
                      <option value=''>Select Provider</option>
                      {providers &&
                        providers.map((provider, id) => (
                          <option key={id} value={provider.id} onClick={()=> setNetworkValue(provider.title.toLowerCase())}>
                            {provider.title}
                          </option>
                        ))}
                    </select>
                  </div>
                  <div className='quick_service_input'>
                    <input
                      id='email'
                      name='amount'
                      type='tel'
                      onChange={(e) => setAmount(e.target.value)}
                      value={amount}
                      placeholder='Amount'
                      required
                    />
                  </div>

                  <div className='quick_service_input'>
                    <input
                      id='email'
                      name='phone_number'
                      type='tel'
                      value={phone_number}
                      onChange={(e) => setPhone_number(e.target.value)}
                      placeholder='Mobile Number'
                      required
                    />
                  </div>
                </>
              ) : serviceKey === "data-subscription" ? (
                <>
                  <div className='quick_service_input'>
                    <select
                      name='network_1'
                      id='cars'
                      className='mb-3'
                      value={network_1}
                      onChange={(e) => getKey(e)}
                      required
                    >
                      <option value=''>Select Provider</option>
                      {providers &&
                        providers.map((provider, id) => (
                          <option key={id} value={provider.key} onClick={()=> setNetworkKey(provider.id)}>
                            {provider.title}
                          </option>
                        ))}
                    </select>
                  </div>
                  <div className='quick_service_input'>
                    <select
                      name='variant'
                      id='cars'
                      className='mb-3'
                      value={variant}
                      onChange={(e) => setVariant(e.target.value)}
                      required
                    >
                      <option value=''>Select Data Value</option>
                      {variantData &&
                        variantData.map((variant, id) => (
                          <option key={id} value={variant.id}>
                            {variant.title} - ₦{variant.price}
                          </option>
                        ))}
                    </select>
                  </div>

                  <div className='quick_service_input'>
                    <input
                      id='email'
                      name='phone_number_1'
                      type='tel'
                      value={phone_number_1}
                      onChange={(e) => setPhone_number_1(e.target.value)}
                      placeholder='Mobile Number'
                      required
                    />
                  </div>
                </>
              ) : serviceKey === "cable_tv" ? (
                <>
                  <div className='quick_service_input'>
                    <select
                      name='cable_tv'
                      id='cars'
                      className='mb-3'
                      value={cable_tv_service}
                      onChange={(e) => getKey(e)}
                      required
                    >
                      <option value=''>Select Service</option>
                      {providers &&
                        providers.map((provider, id) => (
                          <option key={id} value={provider.key}>
                            {provider.title}
                          </option>
                        ))}
                    </select>
                  </div>

                  <div className='quick_service_input'>
                    <select
                      name='cable_tv_package'
                      id='cars'
                      className='mb-3'
                      value={cable_tv_package}
                      onChange={(e) => setCable_tv_package(e.target.value)}
                      required
                    >
                      <option value=''>Select Package (cable tv)</option>
                      {variantData &&
                        variantData.map((variant, id) => (
                          <option key={id} value={variant.key}>
                            {variant.title} - ₦{variant.price}
                          </option>
                        ))}
                    </select>
                  </div>

                  <div className='quick_service_input'>
                    <input
                      id='email'
                      name='cable_tv_smart_card_number'
                      type='tel'
                      value={cable_tv_smart_card_number}
                      placeholder='Smart card number'
                      required
                      onSelect={()=> {if(!cable_tv_service || cable_tv_service === 'Select Service') setError('Please select service')}}
                      onChange={(e) => 
                        {
                          setRes()
                          setErr()
                          !error && setCable_tv_smart_card_number(e.target.value)
                          
                        }
                      }
                    />
                    <p style={{color: error && 'red' || res && '#000152'}}>
                      {error && error}
                      {err && err}
                      {res === 'loading' && !err &&<Spinner color="#000152" size={14}/>}
                      {res && res !== 'loading' && res.content.Customer_Name}
                    </p>
                  </div>
                </>
              ) : (
                <>
                  <div className='radio-row'>
                    <div className='radio__wrapper'>
                      <input
                        id='paid'
                        name='electricity_meter_type'
                        type='radio'
                        onChange={(e) => {
                          setElectricity_meter_type(e.target.value);
                          setError()
                        }}
                        value={"postpaid"}
                        onClick={()=> {
                          setElectricity_meter_number()
                          setRes()
                        }}
                        checked={electricity_meter_type === 'postpaid' ? true : false}
                      />
                      <label htmlFor='paid'>Post Paid</label>
                    </div>
                    <div className='radio__wrapper'>
                      <input
                        id='paidPre'
                        name='electricity_meter_type'
                        type='radio'
                        onChange={(e) => {
                          setElectricity_meter_type(e.target.value);
                          setError()
                        }}
                        onClick={()=> {
                          setElectricity_meter_number()
                          setRes()
                        }}
                        value={"prepaid"}
                        checked={electricity_meter_type === 'prepaid' ? true : false}
                      />
                      <label htmlFor='paidPre'>Pre Paid</label>
                    </div>
                  </div>
                  <div className='quick_service_input'>
                    <select
                      name='electricity'
                      id='cars'
                      className='mb-3'
                      value={electricity_disco}
                      onChange={(e) => getKey(e)}
                      required
                    >
                      <option value=''>Select Provider</option>
                      {providers &&
                        providers.map((provider, id) => (
                          <option onClick={()=> setElectricity_disco_id(provider.id)} key={id} value={provider.key}>
                            {provider.title}
                          </option>
                        ))}
                    </select>
                  </div>

                  <div className='quick_service_input'>
                    <input
                      id='email'
                      name='electricity_amount'
                      type='tel'
                      onChange={(e) => setElectricity_amount(e.target.value)}
                      value={electricity_amount}
                      placeholder='Amount'
                      required
                    />
                  </div>

                  <div className='quick_service_input'>
                    <input
                      id='email'
                      name='electricity_meter_number'
                      type='tel'
                      value={electricity_meter_number}
                      onChange={(e) =>
                        {
                          setErr()
                          setRes()
                          if (!error) setElectricity_meter_number(e.target.value)                              
                        }
                      }
                      placeholder='Meter number'
                      required
                      onSelect={()=> {
                        if(!electricity_disco || electricity_disco === 'Select Provider') setError('Please select provider')
                        if(electricity_disco && !electricity_meter_type) setError('Please select meter type')
                      }}
                    />
                    <p style={{color: error &&'red' || res && '#000152'}}>
                      {error && error}
                      {err && err}
                      {res === 'loading' && !err &&<Spinner color="#000152" size={14}/>}
                      {res && res !== 'loading' && res.content.Customer_Name}
                    </p>
                  </div>
                </>
              )}
              {!spinner && <button type='submit' onClick={(e)=>{
                setModalState(idx)
              }}>Process</button>}
              {spinner && (
                <button type='submit' disabled>
                  <Spinner color='light' />
                </button>
              )}

              {/* <button type='submit'>Process</button> */}
            </form>
          </div>
          <div className='quick__service_img'>
            <img src={IMG1} alt='IMG' className='' />
          </div>
        </div>
        <div className='wallet__information'>
          <div className='hide_on_small_screen'>
            <WalletBallance />
          </div>
          <div className='recent__transaction'>
            <div className='first'>
              <span>
                <img src={IMG2} alt='' />
              </span>
              <p>Recent Transactions</p>
            </div>

            <div className='second'>
              <RecentTransactions />
            </div>
          </div>
        </div>
      </div>
      {res && res !== 'loading' && modalState === 2 && modalState === idx &&
          <div className={'exchange__modal'}>
            <Modal isOpen={modalState}
              onRequestClose={closeModal}
              style={
                {...customStyles, 
                  padding: 10
                }
                
              }
              contentLabel='Example Modal'
              ariaHideApp={false}>
                <div className='acc_modal_header'>
                  <h1>Confirm Bill Payment</h1>
                  {/* <button onClick={closeModal}>close</button> */}
                  <img
                    src={CloseModalIcon}
                    alt='close modal'
                    onClick={closeModal}
                    className='close_modal'
                  />
                </div>
                <div>
                  <ul>
                    <li>Customer name: {res.content.Customer_Name}</li>
                    <li>Due date: {res.content.Due_Date}</li>
                    <li>Customer type: {res.content.Customer_Type}</li>
                    <li>Customer number: {res.content.Customer_Number}</li>
                    <li>Current bouquet: {res.content.Current_Bouquet}</li>
                    <li>Current bouquet code: {res.content.Current_Bouquet_Code}</li>
                    <li>Renewal amount: {res.content.Renewal_Amount}</li>

                  </ul>
                </div>
                
                {!spinner && <button onClick={(e)=>{
                  e.preventDefault()
                  handleSubmit(e)
                  setRes()
                }} type='submit' style={{
                  background: '#ff7f00',
                  boxShadow: '0px 6px 20px rgba(83, 41, 0, 0.08)',
                  borderRadius: '100px',
                  border: 'none',
                  padding: '10px 40px',
                  fontStyle: 'normal',
                  fontWeight: 500,
                  fontSize: '15px',
                  lineHeight: '15px',
                  textAlign: 'center',
                  color: '#ffffff',
                  display: 'block',
                  margin: '20px auto'
                }} >Process</button>}
                {spinner && (
                  <button type='submit' style={{
                    background: '#ff7f00',
                    boxShadow: '0px 6px 20px rgba(83, 41, 0, 0.08)',
                    borderRadius: '100px',
                    border: 'none',
                    padding: '10px 40px',
                    fontStyle: 'normal',
                    fontWeight: 500,
                    fontSize: '15px',
                    lineHeight: '15px',
                    textAlign: 'center',
                    color: '#ffffff',
                    display: 'block',
                    margin: '20px auto'
                  }} disabled>
                    <Spinner color='light' />
                  </button>
                )}
            </Modal>
          </div>
        }
        {res && res !== 'loading' && modalState === 3 && modalState === idx &&
          <div className={'exchange__modal'}>
            <Modal isOpen={modalState}
              onRequestClose={closeModal}
              style={customStyles}
              contentLabel='Example Modal'
              ariaHideApp={false}>
                <div className='acc_modal_header'>
                  <h1>Confirm Bill Payment</h1>
                  {/* <button onClick={closeModal}>close</button> */}
                  <img
                    src={CloseModalIcon}
                    alt='close modal'
                    onClick={closeModal}
                    className='close_modal'
                  />
                </div>
                <div>
                  <ul>
                    <li>Customer name: {res.content.Customer_Name}</li>
                    <li>Customer address: {res.content.Address}</li>
                    <li>Customer Meter number: {res.content.MeterNumber}</li>
                    <li>Current Meter type: {res.content.Meter_Type}</li>
                  </ul>
                </div>
                {!spinner && <button onClick={(e)=>{
                  e.preventDefault()
                  handleSubmit(e)
                  setRes()
                }} type='submit' style={{
                  background: '#ff7f00',
                  boxShadow: '0px 6px 20px rgba(83, 41, 0, 0.08)',
                  borderRadius: '100px',
                  border: 'none',
                  padding: '10px 40px',
                  fontStyle: 'normal',
                  fontWeight: 500,
                  fontSize: '15px',
                  lineHeight: '15px',
                  textAlign: 'center',
                  color: '#ffffff',
                  display: 'block',
                  margin: '20px auto'
                }} >Process</button>}
                {spinner && (
                  <button type='submit' style={{
                    background: '#ff7f00',
                    boxShadow: '0px 6px 20px rgba(83, 41, 0, 0.08)',
                    borderRadius: '100px',
                    border: 'none',
                    padding: '10px 40px',
                    fontStyle: 'normal',
                    fontWeight: 500,
                    fontSize: '15px',
                    lineHeight: '15px',
                    textAlign: 'center',
                    color: '#ffffff',
                    display: 'block',
                    margin: '20px auto'
                  }} disabled>
                    <Spinner color='light' />
                  </button>
                )}
            </Modal>
          </div>
        }
    </div>
  );
};

export default DashboardHome;
