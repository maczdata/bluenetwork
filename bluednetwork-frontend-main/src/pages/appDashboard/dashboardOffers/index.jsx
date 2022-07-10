import { useEffect, useState } from "react"
import MyOffers from "./my offers"
import Offers from "./offers"
import './index.scss'
import axios from "axios"
import { useSelector } from "react-redux"
import { toast, ToastContainer } from "react-toastify"
import rewriteUrl from "../../../helper/rewriteUrl"
const DashboardOffers = () => {

    const [active, setActive] = useState(1)

    const [res, setRes] = useState()

    const [err, setErr] = useState()

    const [myOffers, setMyOffers] = useState()

    const currentUser = useSelector( (state) => state.auth.data)

    const [loading, setLoading] = useState()

    const handleFetchOffers = async () => {

        setLoading(true)

        let response;
        response = await axios(
            {
                method: 'get',
                url: `${rewriteUrl()}front_api/offers/list`
            }
        ).then((response)=>{
            setRes(response.data)
        }).catch((error)=>{
            setErr(error)
            toast(error?.response?.data.message)
        })
    }
    const handleFetchMyOffers = async () => {

        setLoading(true)

        let response
        response = await axios(
            {
                method: 'get',
                url: `${rewriteUrl()}front_api/offers/users/list`,
                params: {
                    user_id: currentUser?.id
                }
            }
        ).then((response)=>{

            setMyOffers(response.data)

            setLoading(false)
        })
        .catch((error)=> {

            setLoading(false)

            toast(error.response.data.message)
        })
    }
    console.log('use', myOffers);
    useEffect(()=>{
        handleFetchOffers()
    }, [])
    return(
        <div className="dashboard_offers">
            <ToastContainer/>
            <div className="offers_nav">
                <p className={active === 1 ? "bill-tab-active": 'bill-tab'} onClick={()=>{
                    setActive(1)
                    handleFetchOffers()
                }}>
                    Offers
                </p>
                <p className={active === 2 ? "bill-tab-active" : 'bill-tab'} onClick={()=>{
                    setActive(2)
                    handleFetchMyOffers()
                }}>
                    My Offers
                </p>
            </div>
            <div className="offersContent">
                {active === 1 &&(
                    <Offers loading={loading} data={res?.data} />
                )}
                {active === 2 &&(
                    <MyOffers handleFetchMyOffers={handleFetchMyOffers} loading={loading} data={myOffers?.data} setActive={setActive}/>
                )}
            </div>
        </div>
    )
}
export default DashboardOffers