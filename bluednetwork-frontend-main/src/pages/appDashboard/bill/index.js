import React, { useEffect, useState, useMemo, useCallback } from "react";
import "./dashboardBills.scss";
import Banner from "../../../assets/Frame 1.png";
import IMG2 from "../../../assets/wallet 2.svg";
import {
  getAllServices,
  getProviderDetails,
  getVariant,
  quickSerice,
} from "../../../redux/actions/service";
import RecentTransactions from "../../../components/RecentTransactions";
import WalletBallance from "../dashboardHome/WalletBallance";
import { FaSpinner } from "react-icons/fa";
import SuccessOrFailModal from "../dashboardExchange/modal";
import { Spinner } from "reactstrap";
import Fade from "../../../components/advert";
import axios from "axios";
import rewriteUrl from "../../../helper/rewriteUrl";
import { toast } from "react-toastify";
import Modal from "react-modal";
import { useDispatch, useSelector } from "react-redux";
import { loadUser } from "../../../redux/actions/auth";
import CloseModalIcon from "../../../assets/closeModalIcon.svg";


const FieldsDisplay = ({ type, title, name, required, providerDetails, handleChange, formControl, variantData, ...props }) => {
  let fieldToDisplay
  switch (type) {
    case "select":
      let optionFields
      if (title === "Variant") {
        optionFields = variantData?.map((variant, id) => (
          <option key={id} value={variant.id}>
            {variant.title} - ₦{variant.price}
          </option>
        ))
      } else {
        optionFields = providerDetails &&
          providerDetails?.map((provider, id) => (
            <option key={id} value={provider.id}>
              {provider.title}
            </option>
          ))
      }
      fieldToDisplay = (
        <select
          id={name}
          name={name}
          value={formControl[name]}
          onChange={handleChange}
          className='mb-3'
          required={required}
        >
          <option>{title}</option>
          {optionFields}
        </select>
      )
      break;
    case "textarea":
      fieldToDisplay = (
        <textarea
          value={formControl[name]}
          onChange={handleChange}
          id={name}
          name={name}
          placeholder={title}
          required={required}
        />
      )

      break;
    case "text":
    default:
      fieldToDisplay = (
        <input
          value={formControl[name]}
          onChange={handleChange}
          id={name}
          name={name}
          type={type}
          placeholder={title}
          required={required}
        />
      )
      break;
  }

  return (
    <div className='quick_service_input' key={name}>
      {fieldToDisplay}
    </div>
  )
  // if(props.type)
}

const DashboardBills = () => {
  useEffect(() => {
    dispatch(getAllServices());
  }, []);

  // get service data from state
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const providerDetails = servicesData?.providerDetails?.data?.children;
  const providerFields = useMemo(() => servicesData?.providerDetails?.data?.fields || [], [servicesData?.providerDetails?.data?.fields]);

  const spinner = servicesData.loading;

  const successOrFail = servicesData?.transaction;
  const fail = servicesData?.payload;

  //
  const bdBills = servicesData.responseTwo?.data?.data;
  const variantData = servicesData?.variant?.data;

  const [fieldsState, setFieldsState] = useState({})

  //
  const [providers, setProviders] = useState([]);
  // save service key to a state
  const [serviceKey, setServiceKey] = useState("airtime-topup");
  // console.log("serviceKey", serviceKey);
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

  const [bdBillsIndex, setBdBillsIndex] = useState("airtime-topup");
  // console.log("bdBillsIndex", bdBillsIndex);

  const reloadFields = useCallback(async () => {
    let fieldsObj = {}
    await providerFields.forEach((field) => {
      fieldsObj[field.key] = ""
    })
    setFieldsState(fieldsObj)
  }, [providerFields])

  useEffect(() => {
    reloadFields()
  }, [reloadFields])
  const handleAirtimeCharge = () => {
    dispatch(quickSerice({ 
      serviceKey, network, amount, phone_number, networkValue, setModalState
    }));
  };

  const handleDataCharge = () => {
    dispatch(quickSerice({ 
      serviceKey, network_1, variant, phone_number_1, networkKey, setModalState
    }));
    // console.log("test", { serviceKey, network_1, variant, phone_number_1 });
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

    const filteredprovider = providerDetails.find((data) => {
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
    // console.log("serviceKey", serviceKey);
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
      setRes()
    } else if (serviceKey === "electricity") {
      handleElectricityCharge();
      setElectricity_disco("");
      setElectricity_amount("");
      setElectricity_meter_number("");
      setElectricity_meter_type("");
      setRes()
    }
  };
  const [preview, setPreview] = useState()

  console.log(serviceKey, spinner, 'keyy');

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
  const handleChange = ({ target: { name, value } }) => {
    setFieldsState((prev) => ({
      ...prev,
      [name]: value
    }))
    console.log({ name })
    if (["cable_tv_service", "network", "network_1", 'electricity_disco'].includes(name)) {
      const filteredprovider = providerDetails.find((data) => {
        if (data.id == value) {
          return data.key;
        }
      });
      const key = filteredprovider.key;
      dispatch(getVariant({ key }));
    }
  }

  useEffect(() => {

    setBdBillsIndex(bdBillsIndex);

    setServiceKey(bdBillsIndex);

    dispatch(getProviderDetails(bdBillsIndex));
  }, []);

  const [error, setError] = useState()

  const [err, setErr] = useState()

  const [res, setRes] = useState()

  const [modalState, setModalState] = useState()

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
  const closeModal = () => {
    setModalState()
    dispatch(loadUser())
  }

  const [idx, setIdx] = useState()

  const [electricity_disco_id, setElectricity_disco_id] = useState()

  console.log(electricity_disco_id, 'iddddd');

  const [networkValue, setNetworkValue] = useState()

  const [networkKey, setNetworkKey] = useState()

  console.log(networkValue, 'valuee');
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
      <div className='display_on_small_screen_bills'>
        <WalletBallance />
      </div>
      <div className='first__sections'>
        <div className='quick__service'>
          <div className='quick__service_form'>
            <h2>Bills Payment</h2>

            <div className=''>
              {bdBills &&
                bdBills.map((bills, index) => (
                  <div
                    key={index}
                    className={
                      bdBillsIndex === bills.key
                        ? "bill-tab-active"
                        : "bill-tab"
                    }
                    onClick={() => {
                      setBdBillsIndex(bills.key);
                      setServiceKey(bills.key);
                      dispatch(getProviderDetails(bills.key));
                      setIdx(index)
                      setError()
                      setRes()
                      setErr()
                    }}
                  >
                    {bills.title}
                  </div>
                ))}
            </div>
          </div>
          <div className='quick__service_data'>
            <form onSubmit={(e)=>{
              e.preventDefault()
              // setPreview('loading')
              if(serviceKey === 'airtime-topup' || serviceKey === 'data-subscription'){
                handleSubmit(e)
              }
              else !res && res === 'loading' && handlePreview()
              }}>
              {/* <div className='provider'>Select Provider</div> */}
              {spinner ?
                <div className="h-100 d-flex align-items-center w-100 justify-content-center">
                  <Spinner color="primary" />
                </div>
                : <>
                  {/* {providerFields && providerFields.map((field, i) => (
                    <FieldsDisplay
                      {...field}
                      name={field.key}
                      providerDetails={providerDetails}
                      variantData={variantData}
                      formControl={fieldsState}
                      handleChange={handleChange} />
                  ))} */}
                  {bdBillsIndex === "data-subscription" ? (
                    <>
                      <div className='quick_service_input'>
                        <label>Select Provider</label>
                        <select
                          name='network_1'
                          id='cars'
                          className='mb-3'
                          value={network_1}
                          onChange={(e) => getKey(e)}
                          required
                        >
                          <option>Select Provider</option>
                          {providerDetails &&
                            providerDetails.map((provider, id) => (
                              <option key={id} value={provider.key} onClick={()=> setNetworkKey(provider.id)}>
                                {provider.title}
                              </option>
                            ))}
                        </select>
                      </div>
                      <div className='quick_service_input'>
                        <label>Select Data Value</label>
                        <select
                          name='variant'
                          id='cars'
                          className='mb-3'
                          value={variant}
                          onChange={(e) => setVariant(e.target.value)}
                          required
                        >
                          <option>Select Data Value</option>
                          {variantData &&
                            variantData.map((variant, id) => (
                              <option key={id} value={variant.id}>
                                {variant.title} - ₦{variant.price}
                              </option>
                            ))}
                        </select>
                      </div>

                      <div className='quick_service_input'>
                        <label>Mobile Number</label>
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
                  ) : bdBillsIndex === "cable_tv" ? (
                    <>
                      <div className='quick_service_input'>
                        <label>Select Service</label>
                        <select
                          name='cable_tv'
                          id='cars'
                          className='mb-3'
                          value={cable_tv_service}
                          onChange={(e) => getKey(e)}
                          required
                        >
                          <option>Select Service</option>
                          {providerDetails &&
                            providerDetails.map((provider, id) => (
                              <option key={id} value={provider.key}>
                                {provider.title}
                              </option>
                            ))}
                        </select>
                      </div>

                      <div className='quick_service_input'>
                        <label>Select Package (cable tv)</label>
                        <select
                          name='cable_tv_package'
                          id='cars'
                          className='mb-3'
                          value={cable_tv_package}
                          onChange={(e) => setCable_tv_package(e.target.value)}
                          required
                        >
                          <option>Select Package (cable tv)</option>
                          {variantData &&
                            variantData.map((variant, id) => (
                              <option key={id} value={variant.key}>
                                {variant.title} - ₦{variant.price}
                              </option>
                            ))}
                        </select>
                      </div>

                      <div className='quick_service_input'>
                        <label>Smart card number</label>
                        <input
                          id='email'
                          name='cable_tv_smart_card_number'
                          type='tel'
                          value={cable_tv_smart_card_number}
                          onSelect={()=> {if(!cable_tv_service || cable_tv_service === 'Select Service') setError('Please select service')}}
                          onChange={(e) => 
                            {
                              setRes()
                              setErr()
                              !error && setCable_tv_smart_card_number(e.target.value)
                              
                            }
                          }
                          placeholder='Smart card number'
                          required
                        />
                        <p style={{color: error && 'red' || res && '#000152'}}>
                          {error && error}
                          {err && err}
                          {res === 'loading' && !err &&<Spinner color="#000152" size={14}/>}
                          {res && res !== 'loading' && res.content.Customer_Name}
                        </p>
                      </div>
                    </>
                  ) : bdBillsIndex === "electricity" ? (
                    <>
                      <div className='quick_service_input'>
                        <label>Select Provider</label>
                        <select
                          name='electricity'
                          id='cars'
                          className='mb-3'
                          value={electricity_disco}
                          onChange={(e) => {
                            getKey(e)}
                          }
                          required
                        >
                          <option>Select Provider</option>
                          {providerDetails &&
                            providerDetails.map((provider, id) => (
                              <option onClick={()=> setElectricity_disco_id(provider.id)} key={id} value={provider.key}>
                                {provider.title}
                              </option>
                            ))}
                        </select>
                      </div>
                      <div className='radio-rows'>
                        <div className='radio__wrappers'>
                          <input
                            id='paid'
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
                            value={"postpaid"}
                            checked={electricity_meter_type === 'postpaid' ? true : false}
                          />
                          <label htmlFor='paid'>Post Paid</label>
                        </div>
                        <div className='radio__wrappers'>
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
                        <label>Amount</label>

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
                        <label>Meter number</label>
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
                          onSelect={()=> {
                            if(!electricity_disco || electricity_disco === 'Select Provider') setError('Please select provider')
                            if(electricity_disco && !electricity_meter_type) setError('Please select meter type')
                          }}
                          placeholder='Meter number'
                          required
                        />
                        <p style={{color: error &&'red' || res && '#000152'}}>
                          {error && error}
                          {err && err}
                          {res === 'loading' && !err &&<Spinner color="#000152" size={14}/>}
                          {res && res !== 'loading' && res.content.Customer_Name}
                        </p>
                      </div>
                    </>
                  ) : (
                    <>
                      <div className='quick_service_input'>
                        <label>Select Provider</label>
                        <select
                          name='network'
                          id='cars'
                          className='mb-2'
                          value={network}
                          onChange={(e) => {
                            setNetwork(e.target.value);
                          }}
                          required
                        >
                          <option>Select Provider</option>
                          {providerDetails &&
                            providerDetails.map((provider, id) => (
                            
                            <option key={id} value={provider.id} onClick={()=> setNetworkValue(provider.title.toLowerCase())}>
                                {provider.title}
                              </option>
                            ))}
                        </select>
                      </div>
                      <div className='quick_service_input'>
                        <label>Amount</label>
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
                        <label>Mobile Number</label>
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
                  )}
                </>
              }
              {!spinner && <button type='submit' onClick={(e)=>{
                setModalState(idx)
              }}>Process</button>}
              {spinner && (
                <button type='submit' disabled>
                  <Spinner color='light' />
                </button>
              )}
            </form>
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
        {res && res !== 'loading' && modalState === 2 && modalState === idx &&
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
    </div>
  );
};

export default DashboardBills;
