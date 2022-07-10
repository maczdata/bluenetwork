import React, { useEffect, useState } from "react";
import "./index.scss";
import SideBar from "../../components/SideBar";
import DashboardNavBar from "../../components/dashboardNavBar";
import { useDispatch, useSelector } from "react-redux";
import { getServices } from "../../redux/actions/service";
import Shield from "../../assets/shield";
import Success from "../../assets/success";
import Cancel from "../../assets/cancel";
import Modal from "./modals";
import EmailKyc from "./modals/email";
import PhoneKyc from "./modals/phone";
import Address from "./modals/address";
import IdKyc from "./modals/id";
import { useLocation } from "react-router-dom";
import rebuild from "./modals/helper/rebuild";
import { FaCircle } from "react-icons/fa";
import useVirtualAccount from "../../components/form/hooks/useVirtualAccount";
// import Bill from "./bill/DashboardHome"

const DashboardBills = (props) => {
  const dispatch = useDispatch()
  const [sidebarIsOpen, setSidebarOpen] = useState(true);
  const toggleSidebar = () => setSidebarOpen(!sidebarIsOpen);
  const closeSidebar = () => setSidebarOpen(true);
  const {handleCreateVirtualAccount} = useVirtualAccount()
  useEffect(() => {
    dispatch(getServices());
    const timer = setTimeout(() => {
      dispatch(getServices());
    }, 1000);
    return () => clearTimeout(timer);
  }, []);
  const currentUser = useSelector( (state) => state.auth.data)
  useEffect(()=>{
    if(!currentUser?.virtual_account || currentUser?.virtual_account === null){
      handleCreateVirtualAccount(currentUser?.email)
    }
  }, [currentUser?.email])
  console.log('user', currentUser)
  const [verification, setVerification] = useState()
  const verData = [
    {
      key: 'Email',
      text: `Email address is `
    },
    {
      key: 'Mobile',
      text: `Mobile number is `,
    },
    
    {
      key: 'Proof of address',
      text: `Address is `,
    },
    {
      key: 'Proof of Identity',
      text: `ID is `,
    }
  ]
  const location = useLocation()
  useEffect(()=>{
    if(verification?.[1] === false) {
      setVerifiationModals({...verificationModals, phone: !verificationModals.phone})
    }
  }, [currentUser])
  useEffect(()=>{
    currentUser && setVerification(rebuild(currentUser))
  }, [currentUser])
  const [verificationModals, setVerifiationModals] = useState({
    email: false,
    phone: false,
    bvn: false,
    address: false,
    id: false
  })
  const closeModal = (pointer) =>{
    pointer === 'email' && setVerifiationModals({email: !verificationModals.email})
    pointer === 'phone' && setVerifiationModals({phone: !verificationModals.phone})
    pointer === 'address' && setVerifiationModals({address: !verificationModals.address})
    pointer === 'bvn' && setVerifiationModals({bvn: !verificationModals.bvn})
    pointer === 'id' && setVerifiationModals({id: !verificationModals.id})
  }
  console.log('this', verification);
 
  return (
    <div className='d-flex dashboard'>
      {verificationModals?.email && <Modal> <EmailKyc closeModal={closeModal}/> </Modal>}
      {verificationModals?.phone && <Modal> <PhoneKyc closeModal={closeModal}/> </Modal>}
      {verificationModals?.bvn && <Modal>  </Modal>}
      {verificationModals?.address && <Modal> <Address closeModal={closeModal}/> </Modal>}
      {verificationModals?.id && <Modal> <IdKyc closeModal={closeModal} /> </Modal>}
      
      <div
        className={
          sidebarIsOpen ? `dashboard-left active` : `dashboard__mobile`
        }
      >
        <SideBar toggleSidebar={toggleSidebar} closeSidebar={closeSidebar} />
      </div>

      <div className='dashboard-right'>
        <DashboardNavBar toggleSidebar={toggleSidebar} />
        <div className="kyc_div">
          <div className="keyHeader">
            <div className="icon">
              <Shield />
            </div>
            <p>
              KYC Verification Checklist.
            </p>
          </div>
          <div className="kyc_items">
            {
              verification?.map((item, idx)=>{
                return <div onClick={()=>{
                  idx === 0 && item.value !== 'verified' && item.value !=='pending' && setVerifiationModals({email: !verificationModals.email})
                  idx === 1 && item.value !== 'verified' && item.value !=='pending' && setVerifiationModals({phone: !verificationModals.phone})
                  idx === 2 && item.value !== 'verified' && item.value !=='pending' && setVerifiationModals({address: !verificationModals.address})
                  // idx === 2 && item.value !== 'verified' && item.value !=='pending' && setVerifiationModals({bvn: !verificationModals.bvn})
                  idx === 3 && item.value !== 'verified' && item.value !=='pending' && setVerifiationModals({id: !verificationModals.id})

                }} className={`kyc_item ${item.value !== 'verified' && item.value !=='pending' && 'hover'}`}>
                  {item.value === 'verified' && <Success />} 
                  {item.value === 'unverified' && <Cancel />}
                  {item.value === 'pending' && <FaCircle fill="orange"/>}
                  <div className="deets">
                    <h5>
                      {verData[idx].key}
                    </h5>
                    <p>
                      {verData[idx].text}
                      <span className={item.value === 'verified' ? 'success' : 'cancel'}>{item.value}</span>
                    </p>
                  </div>
                </div>
              })
            }
          </div>
        </div>
        {/* Dashboard components */}
        <div className='dashboard-components'>
          {/* <DashboardHome isOpen={sidebarIsOpen} /> */}
          {props.children}
          
        </div>
      </div>
    </div>
  );
};

export default DashboardBills;
