// import React, { useEffect } from 'react';
// import Modal from 'react-modal';
// import './index.scss';
// import GiftCardWelcomeImg from '../../assets/gift-card-welcome-img.svg';
// import CloseModalIcon from '../../assets/closeModalIcon.svg';
// import { Link} from 'react-router-dom';
// import { resetAllToFalse } from '../../redux/actions/service';
// import { useDispatch } from 'react-redux';


// const customStyles = {
//     content: {
//       align: 'center',
//       top: '50%',
//       left: '50%',
//       right: 'auto',
//       bottom: 'auto',
//       marginRight: '-50%',
//       transform: 'translate(-50%, -50%)',
//       backgroundColor: 'rgba(0, 0, 0, 0.7)'
//     },
//   };

// const GiftCardExchangeModal = () => {
//   const dispatch = useDispatch();
//     const userName='Janet Adebayo'
//     const [modalIsOpen, setIsOpen] = React.useState(false);
  
//     function openModal() {
//       // setIsOpen(true);
//     }
  
//     function afterOpenModal() {
//     }
  
//     function closeModal() {
//       dispatch(resetAllToFalse())
//     }
  
//     return (
//       <div className="exchange__modal">
//         {/* <button onClick={openModal}>Open Modal</button> */}
//         <Modal
//           isOpen={modalIsOpen}
//           onAfterOpen={afterOpenModal}
//           onRequestClose={closeModal}
//           style={customStyles}
//           contentLabel="Example Modal"
//           ariaHideApp={false}
//         >
//            <img src={CloseModalIcon} alt="close modal" onClick={closeModal} className='close_modal'/>
//           <div className='welcome_service'>
           
//             <img src={GiftCardWelcomeImg} alt="welcome image" />
//             <h3>Welcome, <span>{userName}!</span></h3>
//             <div className='service_terms'>
//             <p>Thank you for choosing to trade with Blue-D Services. We employ strict security measures to ensure your cards are loaded safely and promptly. </p>
//             <p>We will not be responsible in anyway for cards that turn out to have been previously used or are invalid.</p>
//             <p>After starting a trade, wait for instructions from our agent. If the information you’ve provided about your card is accepted, you’ll be given the rate and told to send. Do NOT send card until you’ve been told to do so.</p>
//             <p>WE DO NOT PAY FOR PREVIOUSLY USED OR INVALID CARDS AND WILL NOT BE RESPONSIBLE FOR CARDS SENT WITHOUT OUR AUTHORIZATION.</p>
//             <p>Start a trade only if you have read, understood and accepted our <Link className='link'>Terms of Service</Link>.</p>
//           </div>
//           <button onClick={closeModal}>Start Trading</button>
//           </div>
         
//         </Modal>
//       </div>
//     );
// }

// export default GiftCardExchangeModal
