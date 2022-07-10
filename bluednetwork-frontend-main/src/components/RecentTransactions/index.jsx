import React, { useState, useEffect } from "react";
import IMG5 from "../../assets/Arrow 2 (1).svg";
// import IMG5 from "../../../assets/Arrow 2 (1).svg";
import IMG4 from "../../assets/Arrow 2.svg";
import { useDispatch, useSelector } from "react-redux";
import {
  allTransaction,
  singleTransactions,
} from "../../redux/actions/service";
import EmptyTransactionHistory from "../emptyTransactionHistory";

const RecentTransactions = () => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const { allTransactions } = servicesData;
console.log('ally', allTransactions);
  useEffect(() => {
    dispatch(allTransaction({ per_page: 5, page: 1 }));
  }, []);

  return (
    <div>
      {allTransactions[1]?.meta?.pagination?.total ? (
        Object.entries(allTransactions[1])
          .filter(([key]) => key !== "meta")
          .map(([key, value], i) => (
            <>
              <div
                key={value.id}
                className='transaction'
                onClick={() => dispatch(singleTransactions(value.id))}
              >
                <div

                // className={data.status === "Completed" ? `img-symbol` : `img-symbol-2`}
                >
                  {/* {console.log("color", value)} */}
                  {value.type === "incoming" ? (
                    <div className='img-symbol-2'>
                      <img src={IMG5} alt='' />
                    </div>
                  ) : (
                    <div className='img-symbol'>
                      <img src={IMG4} alt='' />
                    </div>
                  )}
                </div>

                <div className='content'>
                  {Object.entries(value).map(([key, values], i) => (
                    <>
                      <div className='details'>
                        <p className='header'>{values?.main_message}</p>
                        <p className='amount-2'>{values?.sub_message}</p>
                      </div>
                    </>
                  ))}

                  <div className='status'>
                    <p className='provider'>{value.amount_formatted}</p>
                    <p className='dates'>
                      <span // className={data.status === "Completed" ? "completed" : "pending"}
                        className='completed'
                      ></span>
                      {value.updated_at.split(" ")[0]}
                    </p>
                  </div>
                </div>
              </div>
            </>
          ))
      ) : (
        <EmptyTransactionHistory />
      )}
    </div>
  );
}

export default RecentTransactions;
