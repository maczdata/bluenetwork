import React, { useState } from "react";
import { useFlutterwave, closePaymentModal } from "flutterwave-react-v3";
import { useSelector, useDispatch } from "react-redux";
import { quickSerice } from "../../../redux/actions/service";


export default function App({ info }) {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const { data } = auth;
  const email = data?.email;
  const name = data?.first_name + " " + data?.last_name;
  console.log("email", email)

  const config = {
    public_key: process.env.REACT_APP_FLUTTERWAVE_PUBLIC_KEY,
    tx_ref: Date.now(),
    amount: 100,
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

  console.log('public', process.env.REACT_APP_FLUTTERWAVE_PUBLIC_KEY)

  return (
    <div className='App'>

      <button
        onClick={() => {
          handleFlutterPayment({
            callback: (response) => {
              console.log(response);
            //   dispatch(
            //     quickSerice({
            //       amount: response.amount,
            //       ref_number: response.transaction_id,
            //     })
            //   );
            //   window.location.reload(false);
              closePaymentModal(); // this will close the modal programmatically
            },
            onClose: () => {},
          });
        }}
      >
        Procees
      </button>
    </div>
  );
}
