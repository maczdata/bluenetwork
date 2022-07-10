// import React, { useState } from "react";
// import { useFlutterwave, closePaymentModal } from "flutterwave-react-v3";
// import { useSelector, useDispatch } from "react-redux";
// import { quickSerice } from "../../../redux/actions/service";


// export default function App({ data, payload }) {
//   const dispatch = useDispatch();
//   const auth = useSelector((state) => state.auth);
//   const email = auth?.data?.email;
//   const name = auth?.data?.first_name + " " + auth?.data?.last_name;
//   console.log("data", { data, payload })

//   const [payloadData, setPayloadData] = useState(payload)

//   const config = {
//     public_key: process.env.REACT_APP_FLUTTERWAVE_PUBLIC_KEY,
//     tx_ref: Date.now(),
//     // amount: value?.field_amount,
//     currency: "NGN",
//     payment_options: "card,mobilemoney,ussd",
//     customer: {
//       email: email,
//       // phonenumber: '07064586146',
//       name: name,
//     },
//     customizations: {
//       title: "BDS pay",
//       description: "Payment for topup",
//       logo: "https://st2.depositphotos.com/4403291/7418/v/450/depositphotos_74189661-stock-illustration-online-shop-log.jpg",
//     },
//   };
//   const handleFlutterPayment = useFlutterwave(config);

//   return (
//     <div className='App'>

//       <button
//         onClick={() => {
//           handleFlutterPayment({
//             callback: (response) => {
//               console.log(response);
//               console.log({
//                 amount: response.amount,
//                 transaction_id: response.transaction_id,
//                 payloadData,
//                 // field_amount: value?.field_amount,
//                 // field_phone_number: value?.field_phone_number,
//                 company: data?.company
//               })
//               // return
//               dispatch(
//                 quickSerice({
//                   amount: response.amount,
//                   transaction_id: response.transaction_id,
//                   payload,
//                   // field_amount: value?.field_amount,
//                   // field_phone_number: value?.field_phone_number,
//                   company: data?.company
//                 })
//               );
//         }}
//       >
//         Procees
//     </div>
//   );
// }
