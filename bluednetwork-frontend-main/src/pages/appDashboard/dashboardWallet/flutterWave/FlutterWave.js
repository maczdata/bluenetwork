import React, { useState } from "react";
import { useFlutterwave, closePaymentModal } from "flutterwave-react-v3";
import { useSelector, useDispatch } from "react-redux";
import { loadUser } from "../../../../redux/actions/auth";
import { getwalletTopUp } from "../../../../redux/actions/service";

export default function App() {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const email = auth?.data?.email;
  const name = auth?.data?.first_name + " " + auth?.data?.last_name;

  const config = {
    public_key: process.env.REACT_APP_FLUTTERWAVE_PUBLIC_KEY,
    tx_ref: Date.now(),
    // amount: info.amount,
    currency: "NGN",
    payment_options: "card,mobilemoney,ussd",
    customer: {
      email: email,
      // phonenumber: '07064586146',
      name: name,
    },
    customizations: {
      title: "BDS pay",
      description: "Payment for topup",
      logo: "https://st2.depositphotos.com/4403291/7418/v/450/depositphotos_74189661-stock-illustration-online-shop-log.jpg",
    },
  };
  const handleFlutterPayment = useFlutterwave(config);

  return (
    <div className='App'>
      <button
        onClick={() => {
          handleFlutterPayment({
            callback: (response) => {
              console.log(response);
              dispatch(
                getwalletTopUp({
                  amount: response.amount,
                  ref_number: response.transaction_id,
                })
              );
              dispatch(loadUser());
              closePaymentModal(); // this will close the modal programmatically
              setTimeout(() => {
                window.location.reload(false);
              }, 2000);
              // window.location.reload(false);
            },
            onClose: () => {
              // dispatch(loadUser())
            },
          });
        }}
      >
        Top Up Wallet
      </button>
    </div>
  );
}
