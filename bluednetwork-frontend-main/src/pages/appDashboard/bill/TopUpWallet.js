import React, { useState } from "react";
import FlutterWave from "../../../components/flutterWave/FlutterWave";
import Paystack from "../../../components/paystack/Paystack";

const TopUpWallet = ({ displayWalletForm }) => {
  const [formData, SetFormData] = useState({ amount: 0 });

  const { amount } = formData;

  const handleChange = (e) => {
    SetFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

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
        <button onClick={displayWalletForm}  className="wallet__btn">Cancel</button>
        {/* <FlutterWave info={formData} /> */}
        <Paystack info={formData} reloadAmount={reloadAmount} />
      </div>
    </form>
  );
};

export default TopUpWallet;
