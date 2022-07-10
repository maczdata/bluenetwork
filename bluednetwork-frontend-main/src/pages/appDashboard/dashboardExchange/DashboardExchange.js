import React, { useState, useEffect, useCallback } from "react";
import "./dashboardExchange.scss";
import Banner from "../../../assets/Frame 1.png";
import IMG2 from "../../../assets/credit-card.svg";
import WalletBallance from "../dashboardHome/WalletBallance";
import { Spinner } from 'reactstrap';
import AirtimeExchange from "./AirtimeExchange";
import { useDispatch, useSelector } from "react-redux";
import {
  getAllServices,
  getGiftCardDetails,
  getGiftCards,
  getSingleService,
} from "../../../redux/actions/service";
import GiftCardExchangeModal from "../../../components/modal";
import RecentTransactions from "../../../components/RecentTransactions";
import GiftCardmodal from "./giftCardModal";
import SuccessOrFailModal from "./modal";
import Fade from "../../../components/advert";
import axios from "axios";
import rewriteUrl from "../../../helper/rewriteUrl";

const DashboardExchange = () => {


  const dispatch = useDispatch();
  const servicesData = useSelector((state) => state.service);
  const [initLoading, setIntLoading] = useState(true)
  const [toggleExchange, setToggleExchange] = useState(false);
  const [showGiftModal, setShowGiftModal] = useState(false);
  const { loading } = servicesData;
  const successOrFail = servicesData?.giftCardSuccess;
  // const giftCards = servicesData?.giftCards?.data;
  const giftCardsDetails = servicesData?.giftCardsDetails?.data;
  const [filteredData, setFilteredData] = useState([]);
  const [giftCards, setGiftCards] = useState([]);
  const service = servicesData?.service?.data;

  const [buttonData, setButtonData] = useState([])
  // console.log("filteredData", filteredData, giftCards);

  const getServiceData = useCallback(async () => {
    if (service) {
      let serviceData = service.filter((a) => a.title.replace(" ", "_").toLowerCase() === "bd_swap")[0]
      let serviceId = serviceData?.id
      try {
        // updateState({ initFetching: true })
        let { data } = await getSingleService(serviceId)
        console.log('diddy', data);
        let disabledArray =[]
        for (let i = 0; i < data?.data[0]?.services?.length; i++) {
          console.log('stat loop');
          const element = data?.data[0]?.services[i];
          
          if(element.key !== 'gift-card-exchange') {disabledArray.push(element.key)}
          
        }
        console.log('disabled', disabledArray)
        // if(disabledArray.includes("gift-card-exchange")){
        //   setToggleExchange(true)
        // }
        console.log('toggle', toggleExchange)
        setButtonData(disabledArray)
      } catch (error) {

      }

      setIntLoading(false)
    }

  }, [service])


  useEffect(() => {
    getServiceData()
}, [getServiceData])

  useEffect(() => {
    dispatch(getAllServices());
    dispatch(getGiftCards());
    axios(`${rewriteUrl()}front_api/gift_card/list`)
      .then((response) => {
        // console.log("response.data", response?.data?.data);
        setGiftCards(response?.data?.data);
        setFilteredData(response?.data?.data);
      })
      .catch((error) => {
        console.log("Error getting fake data: " + error);
      });
  }, []);

  const showGiftCardModal = (id) => {
    dispatch(getGiftCardDetails(id));
    setShowGiftModal(true);
  };

  const closeGiftCardModal = () => {
    setShowGiftModal(false);
  };

  const handleSearch = (event) => {
    let value = event.target.value;
    let result = [];
    console.log("value", value);
    result = giftCards.filter((data) => {
      return data.title.search(value) != -1;
    });
    setFilteredData(result);
  };
  
  if (initLoading === true)
  return <div className="d-flex align-items-center justify-content-center vh-100"> <Spinner size="xl" /></div>


  return (
    <div className='dashboard-components'>
      {servicesData?.isGiftCardSuccess && (
        <SuccessOrFailModal data={successOrFail} />
      )}
      {showGiftModal && (
        <GiftCardmodal
          data={giftCardsDetails}
          closeGiftCardModal={closeGiftCardModal}
          loading={loading}
        />
      )}

      <div className='barner__container mt-3'>
        <Fade />
      </div>
      <div className='display_on_small_screen_exchange'>
        <WalletBallance />
      </div>
      <div className='first__section'>
        <div className='exchanges_container'>
          <div className='exchanges_buttons'>
            {/* {buttonData.includes("gift-card-exchange") &&<button
              onClick={() => setToggleExchange()}
              className={!toggleExchange ? `dark` : `light`}
            >
              Gift Card Exchange
            </button>} */}
            <button
              onClick={() => setToggleExchange(true)}
              className={!toggleExchange ? `dark` : `light`}
            >
              Airtime Exchange
            </button>
          </div>
          {/* {!toggleExchange ? (
            <div className='gift_card_exchange'>
              <h4 className='instant_exchange'>Instant Exchange for Cash</h4>
              <p className='select_supplier'>Select Gift Card Supplier</p>
              <div className="search__wrapper">
                <input type='text' placeholder="search for your gift cards..." onChange={(event) => handleSearch(event)} />
              </div>
              <div className='card_suppliers'>
                {filteredData &&
                  filteredData?.map((cardSupplier) => (
                    <div
                      className='card_supplier'
                      onClick={() => showGiftCardModal(cardSupplier.id)}
                    >
                      <img src={cardSupplier.gift_card_image} alt='' />
                      <p>{cardSupplier.title}</p>
                    </div>
                  ))}
              </div>
            </div>
          ) : ( */}
            <AirtimeExchange />
          {/* )} */}
        </div>

        <div className='wallet__information'>
          <div className='hide_on_small_screen'>
            <WalletBallance />
          </div>
          <div className='recent__transaction'>
            <div className='first'>
              <span>
                <img src={IMG2} alt='' />
              </span>
              <p>Recent Transactions</p>
            </div>

            <div className='second'>
              <RecentTransactions />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DashboardExchange;
