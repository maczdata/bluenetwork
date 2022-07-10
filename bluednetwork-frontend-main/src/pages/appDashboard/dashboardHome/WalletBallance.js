import React, { useState, useEffect } from "react";
import TopUpWallet from "./TopUpWallet";
import IMG2 from "../../../assets/wallet 2.svg";
import { loadUser } from "../../../redux/actions/auth";
import { useDispatch, useSelector } from "react-redux";

const WalletBallance = () => {
    const dispatch = useDispatch()
    const auth = useSelector(state => state.auth)
    const {data} = auth

    useEffect(() => {
        dispatch(loadUser())
    }, [])


  const [showWalletForm, setShowWalletForm] = useState(false);
  const displayWalletForm = () => {
    setShowWalletForm(!showWalletForm);
  };
  const [fundType, setFundType] = useState()
  return (
    <div className='wallet_balance'>
      <div className='first'>
        <span>
          <img src={IMG2} alt='' />
        </span>
        <p>Wallet Balance</p>
      </div>
      <div className='second'>
        <span className='wallet__amount'>{data?.formatted_wallet_balance}</span>
      </div>

      <div className='third'>
        {!showWalletForm ? (
          <button onClick={()=>{
            displayWalletForm()
            setFundType()
          }}>Top Up Wallet</button>
        ) : 
          (!fundType && showWalletForm && <div className="topup_options">
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
          </div>)
        }
        {
          fundType === 'card' && showWalletForm && <TopUpWallet displayWalletForm={displayWalletForm} />
        }
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
                  {data?.virtual_account?.data?.accountNumber}
                </p>
              </div>
            </div>
            <div>
              <h5>
                Bank name
              </h5>
              <div>
                <p>
                  {data?.virtual_account?.data?.bankName}
                </p>
              </div>
            </div>
            <div>
              <h5>
                Account name
              </h5>
              <div>
                <p>
                  {data?.virtual_account?.data?.customerName}
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
    </div>
  );
};

export default WalletBallance;
