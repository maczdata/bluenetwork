import axios from "axios";
import { useEffect, useState } from "react";
import rewriteUrl from "../helper/rewriteUrl";

const useGetServices = async() => {

    const [serviceType,  setServiceType] = useState()

    const getAllServiceTypes = async () =>{
        let response
        response = await axios.get(
            `${rewriteUrl()}front_api/service/types`,
        ).then((response)=>{
            if(response.data)setServiceType(response)
        })
        setInterval(()=>{
            getAllServiceTypes()
        }, 60000)
    }

    useEffect(()=>{
        getAllServiceTypes()
    }, [])
    return{
        serviceType
    }
}
export default useGetServices
