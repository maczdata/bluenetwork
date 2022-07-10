import React, { useState, useEffect } from "react";
import TopUpWallet from "./TopUpWallet";
import IMG2 from "../../../assets/wallet 2.svg";
import { loadUser } from "../../../redux/actions/auth";
import { useDispatch, useSelector } from "react-redux";

const WalletBallance = () => {
    const dispatch = useDispatch()
    const auth = useSelector(state => state.auth)
    const walletBalance =  auth?.data?.wallet_balance

    useEffect(() => {
        dispatch(loadUser())
    }, [])


  const [showWalletForm, setShowWalletForm] = useState(false);
  const displayWalletForm = () => {
    setShowWalletForm(!showWalletForm);
  };

  return (
    <div className='wallet_balance'>
      <div className='first'>
        <span>
          <img src={IMG2} alt='' />
        </span>
        <p>Wallet Balance</p>
      </div>
      <div className='second'>
        <span className='wallet__n'>N</span>
        <span className='wallet__amount'>{walletBalance}.00</span>
      </div>

      <div className='third'>
        {!showWalletForm ? (
          <button onClick={displayWalletForm}>Top Up Wallet</button>
        ) : (
          <TopUpWallet displayWalletForm={displayWalletForm} />
        )}
      </div>
    </div>
  );
};

export default WalletBallance;
