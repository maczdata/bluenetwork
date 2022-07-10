import axios from "axios"
import { useState } from "react"
import { toast } from "react-toastify"
import rewriteUrl from "../../../../../helper/rewriteUrl"

const useGetAcctName = () =>{
    const token = localStorage.getItem('access_token')
    const [acctName, setAcctName] = useState()
    const getAcctName = async (data) => {
        let response

        response = await axios(
            {
                method: 'post',
                url: `${rewriteUrl()}front_api/account/get_bank_data`,
                params: {
                    account_number: data.account_number,
                    bank_id: data.bank_id,
                    token: token
                }
            }
        ).then((res)=> {
            setAcctName(res.data)
        }).catch((err)=> toast.error(err.response.data.message))
    }
    return{
        acctName,
        getAcctName,
        setAcctName
    }
}
export default useGetAcctName