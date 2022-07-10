import React, { useEffect, useState } from "react";
import "./airtimeExchange.scss";
import MTNLogo from "../../../assets/mtn-logo.svg";
import GloLogo from "../../../assets/glo-logo.svg";
// import NineMobileLogo from "../../../assets/9mobile-logo.svg";
import AirtelLogo from "../../../assets/airtellogo.svg";
import {
  airtimeExchange,
  getAirtimeData,
  quickSerice,
} from "../../../redux/actions/service";
import { useDispatch, useSelector } from "react-redux";
import { FaSpinner } from "react-icons/fa";
import SuccessOrFailModal from "./modal";
import ConfirmModal from "./confirmModal";

const AirtimeExchange = () => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const airtimeProvider = servicesData?.airtimeDetails?.data?.children;
  // console.log("servicesData", airtimeProvider);
  const spinner = servicesData.loading;
  const successOrFail = servicesData?.AirtimeExchange;
  console.log('servicesData', servicesData);
  const [serviceKey, setServiceKey] = useState("airtime-topup");
  const [airtime_for_cash_network, setAirtime_for_cash_network] = useState("");
  const [airtime_amount_transferred, setAirtime_amount_transferred] =
    useState("");
  const [
    airtime_for_cash_sender_phone_number,
    setAirtime_for_cash_sender_phone_number,
  ] = useState("");
  const [phoneData, setPhoneData] = useState("");
  const [showConfirmModal, setShowConfirmModal] = useState(false);
  const [formData, setFormData] = useState("");
  const [validateAmount, setValidateAmount] = useState(false);

  const closeAirtimeModal = () => {
    setShowConfirmModal(false);
  };

  const getPhoneData = (e) => {
    const value = e.target.value;
    setAirtime_for_cash_network(value);
    const filteredprovider = airtimeProvider.find((data) => {
      if (data.id == value) {
        return data.meta;
      }
    });
    return setPhoneData(filteredprovider);
  };

  const reloadFields = ()=>{
    setAirtime_for_cash_sender_phone_number("")
    setAirtime_amount_transferred("")
    setAirtime_for_cash_network("")
  }
  const handleSubmit = (e) => {
    e.preventDefault();
    setFormData({
      airtime_for_cash_network: airtime_for_cash_network,
      airtime_amount_transferred: airtime_amount_transferred,
      airtime_for_cash_sender_phone_number:
        airtime_for_cash_sender_phone_number,
    });
    if (airtime_amount_transferred < 1000) {
      setValidateAmount(true);
      setTimeout(() => setValidateAmount(false), 2000);
    } else {
      setShowConfirmModal(true);
    }
  };

  useEffect(() => {
    dispatch(getAirtimeData());
  }, [phoneData, setPhoneData]);

  return (
    <div>
      {servicesData?.isAirtimeExchange && (
        <SuccessOrFailModal data={successOrFail} />
      )}
      {showConfirmModal && (
        <ConfirmModal
          data={formData}
          phoneData={phoneData}
          reloadFields={reloadFields}
          closeAirtimeModal={closeAirtimeModal}
          amountToReceive={(airtime_amount_transferred !== "" && phoneData !== "")? airtime_amount_transferred - (airtime_amount_transferred * (phoneData?.meta?.percentage_taken/100)) : 0}
          setPhoneData={setPhoneData}
        />
      )}
      <div className='airtime_exchange'>
        <div className='steps_and_exchange_rate'>
          <h4 className='airtime'>Airtime Exchange</h4>
          <p className='steps_header'>Steps</p>
          <ol className='steps'>
            <li>
              Select the network provider you are sending the airtime from.
            </li>
            <li>Enter the airtime amount.</li>
            <li>
              Provide the source mobile number you are sending the airtime from.
            </li>
            <li>Send Airtime to provided mobile number.</li>
          </ol>
        </div>
        <div className='quick__service_form'>
          <h2>Select network provider</h2>
          <div className='network-data'>
            <div className='quick_service_input'>
              <label>Select Provider</label>
              <select
                name='airtime_for_cash_network'
                id='cars'
                className='mb-1'
                onChange={(e) => getPhoneData(e)}
                value={airtime_for_cash_network}
                required
              >
                <option value=''>Select Provider</option>
                {airtimeProvider &&
                  airtimeProvider.map((data) => (
                    <option key={data.id} value={data.id}>
                      {data.title}
                    </option>
                  ))}
              </select>
              <p style={{marginBottom:15, padding:0}}>{phoneData ? `${phoneData?.meta?.percentage_taken} % will be charged` : null}</p>
            </div>
          </div>
          <form onSubmit={handleSubmit}>
            <div className='quick_service_input'>
            <label>Amount</label>
              <input
                id='amount'
                name='airtime_amount_transferred'
                type='tel'
                onChange={(e) => {
                  setAirtime_amount_transferred(e.target.value);
                }}
                value={airtime_amount_transferred}
                placeholder='Amount'
              />
            </div>
            {validateAmount && <span>Amount cannot be less than 1000</span>}
            {(airtime_amount_transferred !== "" && phoneData !== "") && <span style={{
              marginBottom: 20,
              display: 'block'
            }}>You will receive &#8358;{airtime_amount_transferred - (airtime_amount_transferred * (phoneData?.meta?.percentage_taken/100))}</span>}
            <div className='quick_service_input'>
            <label>Sender Mobile Number</label>
              <input
                id='sender_mobile_number'
                name='airtime_for_cash_sender_phone_number'
                type='tel'
                onChange={(e) => {
                  setAirtime_for_cash_sender_phone_number(e.target.value);
                }}
                value={airtime_for_cash_sender_phone_number}
                placeholder='Sender Mobile Number'
              />
            </div>
            <button type='submit'>Process</button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default AirtimeExchange;
