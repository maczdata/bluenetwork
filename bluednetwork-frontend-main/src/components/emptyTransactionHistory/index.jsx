import React from 'react';
import './index.scss';
import TransactionImg from '../../assets/transaction-history-img.svg'

const EmptyTransactionHistory = () => {
    return (
        <div className='gift_card_history'>
            <p>You have not carried out any Exchange Transactions yet, initiate a transaction to view your history here.</p>
            <img src={TransactionImg} alt='No transaction history image'/>

        </div>
    )
}

export default EmptyTransactionHistory
