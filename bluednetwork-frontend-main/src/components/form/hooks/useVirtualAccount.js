import axios from "axios"
import { useState } from "react"

const useVirtualAccount = () =>{
    const [response, setResponse] = useState()
    const [error, setError] = useState()
    const handleCreateVirtualAccount = async (email) =>{
        let response = await axios(
            {
                method: 'post',
                url: 'https://control.bluednetwork.com/front_api/account/create-virtual-account',
                params:{
                    email: email
                }
            }
        ).then((response)=>{
            console.log(response, 'resst');
        }).catch((error)=>{
            console.log(error , 'resst');
        })
    }
    return{
        handleCreateVirtualAccount
    }
}
export default useVirtualAccount