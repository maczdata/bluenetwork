import React, { useState, useEffect } from "react";
import "./index.scss";
import IMG2 from "../../../assets/credit-card.svg";
import IMG5 from "../../../assets/Arrow 2 (1).svg";
import IMG4 from "../../../assets/Arrow 2.svg";
import FilterIcon from "../../../assets/filter-icon.svg";
import TransactionsDetails from "./TransactionsDetails";
import { useDispatch, useSelector } from "react-redux";
import {
  allTransaction,
  singleTransactions,
} from "../../../redux/actions/service";
import EmptyTransactionHistory from "../../../components/emptyTransactionHistory";

const Transactions = () => {
  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const { allTransactions } = servicesData;

  const transaction_info = servicesData?.singleTransactions[1];

  const [transaction_type, setTransaction_type] = useState("all");
  const [per_page, setPer_page] = useState(10);
  const [page, setPage] = useState(1);

  const [show, setShow] = useState(false);

  useEffect(() => {
    dispatch(allTransaction({ per_page: 10, page: 1, transaction_type: transaction_type}));
  }, []);

  const options = [
    {
      label: 'All',
      value: 'all'
    },
    {
      label: "Outgoing Transactions",
      value: "outgoing",
    },
    {
      label: "Incoming Transactions",
      value: "incoming",
    },
    
  ];

  // handleChange to get service key
  useEffect(()=>{
    dispatch(allTransaction({ transaction_type, per_page, page }));
  }, [transaction_type])
  

  console.log(transaction_type)
  return (
    <div>
      <div className='transactions_container'>
        <div className='transactions'>
          <div className='first'>
            <div className='d-flex align-items-center'>
              <span>
                <img src={IMG2} alt='' />
              </span>
              <p>Transactions</p>
            </div>
            <div className='form__filter__wrapper'>
              <form className='mt-3'>
                <select
                  className='select'
                  value={transaction_type}
                  onChange={(e)=>setTransaction_type(e.target.value)}
                  required
                >
                  
                  {options.map((option, index) => (
                    <option key={index} value={option.value}>
                      {option.label}
                    </option>
                  ))}
                </select>
              </form>
            </div>
          </div>
          <div className='second'>
            <img src={FilterIcon} alt='' />
          </div>
          <div className='third'>
            {allTransactions[1]?.meta?.pagination?.total ? (
              Object.entries(allTransactions[1])
                .filter(([key]) => key !== "meta")
                .map(([key, value], i) => (
                  <>
                    <div
                      key={value.id}
                      className='transaction'
                      onClick={() => {
                        dispatch(singleTransactions(value.id));
                        setShow(true);
                      }}
                    >

                        {value.type === "incoming"? (
                          <div className='img-symbol'>
                            <img src={IMG5} alt='' />
                          </div>
                        ) : (
                          <div className='img-symbol-2'>
                            <img src={IMG4} alt='' />
                          </div>
                        )}
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
                            <span 
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
        </div>
        {show && <TransactionsDetails data={transaction_info} />}
      </div>
    </div>
  );
};

export default Transactions;
