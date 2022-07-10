import React, { useRef, useState, useCallback } from "react";

const RenderRates = ({ card, getRateQuantity }) => {
  const [count, setCount] = useState(0);
  // console.log("card", card);

  // let arr = []

  // const cardRef = useRef([])

  const [cardInfo, setCardInfo] = useState([]);

  const qtyRef = useRef(0);

  const increase = useCallback(
    (rateId) => {
      // qtyRef.current = qtyRef.current + 1;
      // setCount(qtyRef.current);
      // console.log("qtyRef.current", {
      //   quantity: qtyRef.current,
      //   rate_id: rateId,
      // });
      // setCardInfo({
      //   quantity: qtyRef.current,
      //   rate_id: rateId,
      // });

      const productInCart = cardInfo.find((item) => item.rate_id === rateId);

      if (productInCart) {
        setCardInfo(
          cardInfo.map((item) =>
            item.rate_id === rateId
              ? { ...item, quantity: item.quantity + 1 }
              : { ...item }
          )
        );
      } else {
        // console.log('creating new value', cardInfo);
        setCardInfo([...cardInfo, { rate_id: rateId, quantity: 1 }]);
      }

      // getCardInfo(rateId, qtyRef.current);
      // const id = card.id
      // const value = {rate_value: card.rate_value, rate_id: card.id, quantity: count}
      // getRateQuantity(value)
      // console.log("cardInfo", cardInfo);
    },
    [cardInfo, setCardInfo]
  );

  const decrease = () => {
    qtyRef.current = qtyRef.current - 1;
    count > 0 && setCount(qtyRef.current);
    // console.log("qtyRef.current", qtyRef.current);
    // const id = card.id
    // const value = {rate_value: card.rate_value, rate_id: card.id, quantity: count}
    // getRateQuantity(value)
  };

  return (
    <div className='single_card_value'>
      <div className='card_rate_value'>{card.rate_value}</div>
      <div className='quantity_wrapper'>
        <div className='increase' onClick={decrease}>
          -
        </div>
        <div className='quantity_amount'>{count}</div>
        <div className='increase' onClick={() => increase(card.id)}>
          +
        </div>
      </div>
    </div>
  );
};

export default RenderRates;

// const getCardInfo = (rateId, quantity) => {
//   const found = cardRef.current.find((info) => {
//     return info == rateId;
//   });

//   // const arr = cardInfo;

//   if (!found) {
//     console.log("not found")
//     // const arr = [{ rate_id: rateId, quantity }];
//     // setCardInfo([...cardInfo, ...arr]);
//     // cardRef.current = [...cardRef.current, rateId]
//     arr.push(rateId);
//   } else {
//     // cardInfo.map((info) => {

//     //   if (info.rateId === rateId) {
//     //     info.quantity = quantity;
//     //   }
//     // });
//     console.log("found")
//   }
//   console.log("found", found);
//   console.log("cardInfo", arr);
// };
// // if (!found) {
// //   cardInfo.push({
// //     rate_id: rateId,
// //     quantity: count,
// //   });
// //   setCardInfo(cardInfo =>  [...cardInfo, {rate_id: rateId, quantity: count }]);
// // }
// // console.log("found", found);
// // console.log("cardInfo", cardInfo);
// // };

// const qtyRef = useRef(0);

// const increase = (rateId) => {
//   qtyRef.current = qtyRef.current + 1;
//   setCount(qtyRef.current);
//   console.log("qtyRef.current", qtyRef.current);
//   getCardInfo(rateId, qtyRef.current);
//   // const id = card.id
//   // const value = {rate_value: card.rate_value, rate_id: card.id, quantity: count}
//   // getRateQuantity(value)
// };

// const decrease = () => {
//   qtyRef.current = qtyRef.current - 1;
//   count > 0 && setCount(qtyRef.current);
//   // const id = card.id
//   // const value = {rate_value: card.rate_value, rate_id: card.id, quantity: count}
//   // getRateQuantity(value)
// };
