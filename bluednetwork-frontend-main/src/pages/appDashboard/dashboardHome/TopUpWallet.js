import React, { useEffect, useState } from "react";
import { useDispatch } from "react-redux";
import FlutterWave from "../../../components/flutterWave/FlutterWave";
import Paystack from "../../../components/paystack/Paystack";
import { loadUser } from "../../../redux/actions/auth";

const TopUpWallet = ({ displayWalletForm }) => {
  const dispatch = useDispatch();
  const [formData, SetFormData] = useState({ amount: 0 });

  const { amount } = formData;

  const handleChange = (e) => {
    SetFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  useEffect(() => {
    dispatch(loadUser());
  }, []);

  const handleSubmit = (e) => {
    e.preventDefault();
  };

  const reloadAmount = () => {
    SetFormData({ amount: 0 })
  }

  return (
    <form onSubmit={handleSubmit}>
      <div className="wallet__input">
        <input
          id='amount'
          name='amount'
          type='tel'
          onChange={(e) => handleChange(e)}
          value={amount}
          placeholder='Amount'
        />
      </div>
      <div className="wallet__buttons">
        {displayWalletForm && <button onClick={displayWalletForm} className="wallet__btn">Cancel</button>}
        {/* <FlutterWave info={formData}  className="wallet__btn"/> */}
        <Paystack info={formData} reloadAmount={reloadAmount} className="wallet__btn" />
      </div>
    </form>
  );
};

export default TopUpWallet;
