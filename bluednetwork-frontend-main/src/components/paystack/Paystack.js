import React, { useState } from "react";
import { useSelector, useDispatch } from "react-redux";
import { getwalletTopUp } from "../../redux/actions/service";
import { loadUser } from "../../redux/actions/auth";
import { PaystackButton } from 'react-paystack';

export default function Paystack({ info, reloadAmount }) {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const email = auth?.data?.email;
  const name = auth?.data?.first_name + " " + auth?.data?.last_name;
  let publicKey = process.env.REACT_APP_PAYSTACK_KEY || "pk_test_92cf22275e565639009b8e969ba444fa70501159"
  const componentProps = {
    email,
    amount: (Number(info.amount) * 100),
    metadata: {
      name,
      // phone,
    },
    publicKey,
    text: "Top Up Wallet",
    onSuccess: (res) => {
      dispatch(
        getwalletTopUp({
          amount: info.amount,
          ref_number: res.trxref,
        },
        reloadAmount)
      );
      // dispatch(loadUser(()=> window.location.reload(false)));
    },
    onClose: () => {},
  }

  
  return (
    <div className='App'>
      <PaystackButton
        // text="Top Up Wallet"
        class="payButton"
        {...componentProps}
        // callback={(response) => {
        //   console.log(response);
        //   // dispatch(
        //   //   getwalletTopUp({
        //   //     amount: response.amount,
        //   //     ref_number: response.transaction_id,
        //   //   })
        //   // );
        //   // dispatch(loadUser());
        //   // closePaymentModal(); // this will close the modal programmatically
        //   // window.location.reload(false);
        // }}
        // close={() => { }}
        // disabled={true}
        // embed={true}
        // reference={Date.now()}
        // email={email}
        // amount={info.amount}
        // paystackkey={`pk_test_600cb90e0aefc5296c9e2a7333aab4d9c8c6bf52`}
        // tag="button"
      />
    </div>
  );
}
