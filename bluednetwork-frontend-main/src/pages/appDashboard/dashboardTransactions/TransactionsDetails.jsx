import React from "react";
import PrintIcon from "../../../assets/print-icon.svg";
import ShareIcon from "../../../assets/share-icon.svg";
import RefreshIcon from "../../../assets/refresh-icon.svg";

const TransactionsDetails = ({ data }) => {
  console.log("data from me", data);

  return (
    <div>
      <div className='transactions_details_container'>
        <div className='top_transactions_details'>
          <div className='transactions_details_left_column'>
            <h4 className='transactions_details_header'>Transaction Details</h4>
            {data &&
              Object.entries(data).map(([key, value], i) => (
                <>
                  <h4 className='transactions_details_header_2'>
                    {value?.main_message}
                  </h4>
                  <div className='transacton_detail'>
                    <p>{value?.sub_message}</p>
                  </div>
                </>
              ))}
            <div className='transacton_details'>
              <p>Date</p>
              <p>{data?.updated_at.split(" ")[0]}</p>
            </div>
          </div>

          <div className='transactions_details_right_column'>
            <p className='transaction_id'>Transaction ID #{data?.reference}</p>
            <p className={'completed'} style={{
              background: data?.status !== 1 && '#ff7f00'
            }}>
              {data?.status === 1 ? "completed" : "pending"}
            </p>
            {/* <button className='refresh_button'>
              Run again <img src={RefreshIcon} />
            </button> */}
          </div>
        </div>

        <div className='bottom_transactions_details'>
          <p className='amount'>Amount</p>
          <p className='total_amount'>{data?.amount_formatted}</p>
        </div>
        <div className='bottom_transactions_details_1'>
          <div
            className={
              data?.title === "Bills - Electricity" ? "token_container" : ""
            }
          >
            {data?.title === "Bills - Electricity" ? (
              <>
                <p>Token</p>
                <p>{data?.token}</p>
              </>
            ) : (
              ""
            )}
          </div>
          {/* <div className='icons_container'>
            <div>
              <img
                src={PrintIcon}
                alt='Print transaction icon'
                className='icons'
              />
            </div>
            <div>
              <img
                src={ShareIcon}
                alt='Share transaction icon'
                className='icons'
              />
            </div>
          </div> */}
        </div>
      </div>
    </div>
  );
};

export default TransactionsDetails;
