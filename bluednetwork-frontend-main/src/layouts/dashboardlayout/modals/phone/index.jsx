import axios from 'axios'
import { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import X from '../../../../assets/x'
import './index.scss'
import Ring from '@bit/joshk.react-spinners-css.ring';
import { toast } from 'react-toastify'
import { loadUser } from '../../../../redux/actions/auth'
import rewriteUrl from '../../../../helper/rewriteUrl'

const PhoneKyc = ({closeModal}) => {
    const [err, setErr] = useState()
    let [res, setRes] = useState()
    const [success, setSuccess] = useState()
    const currentUser = useSelector( (state) => state.auth.data)

    const dispatch = useDispatch()

    const resendPhone = async () => {
        let response

        response = await axios.post(`${rewriteUrl()}front_api/verification/resend`, null, {
            params: {
                source: formData.source,
                source_value: formData.source_value,
                user_id: formData?.userId
            }
        }).then((response)=>{
            setLoading(false)
            setRes(response.data)
        }).catch((err)=>{
            setLoading(false)
            toast.error(err.response.data.message)
            console.log('err', err.response)
            setErr(err.response.data.message)
        })
    }

    const sendOtp = async () => {
        let response

        response = await axios(
            {
                method: 'post',
                url: `${rewriteUrl()}front_api/verification/verify`,
                params: {
                    verification_source: formData.source,
                    source_value: formData.source_value,
                    verification_token: formData?.code,
                    user_id: formData?.userId
                }
            }
        ).then((response)=>{
            setSuccess(response.data.message)
            setLoading(false)
            toast.success(response.data.message)
            dispatch(loadUser())
        }).catch((error)=>{
            setErr(error.response.data.message)
            toast.error(error.response.data.message)
            setLoading(false)
        })
    }

    console.log('res', res)

    const [formData, setFormData] = useState({})

    useEffect(()=>{
        setFormData({
            ...formData,
            source: 'phone_number',
            source_value: currentUser?.phone_number,
            userId: currentUser?.id
        })
    }, [])

    const [loading, setLoading] = useState()
    return(
        !success ? 
        (<form onSubmit={(e)=>{
            e.preventDefault()
            setErr()
            !res && resendPhone()
        }} action="">
            <div className="x" >
                <h5>
                    Verify Phone
                </h5>
                <div className="xclass" onClick={()=> closeModal('phone')}>
                    <X />
                </div>
            </div>
            
            {
                res && <label htmlFor="" style={{
                    display: 'block'
                }}>
                <p>
                    Verification code sent!
                </p>
                <input onChange={(e)=> setFormData({...formData, code: e.target.value})} type="text" placeholder="Please input verification code" required/>
            </label>
            }
            {!res &&<label htmlFor="" style={{
                marginBottom: '20px',
                display: 'block'
            }}>
                <p>Please input phone number</p>
                {!currentUser.phone_number && <input style={{
                marginBottom: '20px',
                display: 'block'
            }} onChange={(e) => setFormData({...formData, source_value: e.target.value})} required/>}
                <button onClick={()=> formData.source_value.length === 11 && setLoading(true)}>Send verification code <span>{loading && <Ring color={'white'} size={16}/>}</span></button>
            </label>}
            {
                res && <label htmlFor="">
                <div className='button' onClick={()=>{
                    if(formData?.code){
                        sendOtp()
                    }
                    else{
                        setErr('Input Code !')
                    }
                }}>Submit code <span>{loading && <Ring color={'white'} size={16}/>}</span> </div>
            </label>
            }
            <p className='err'>
                {
                    err && err
                }
            </p>
            
        </form>)
        :
        <div className="success_screen">
            <p style={{
                textAlign: 'center'
            }}>
                {success} !
            </p>
            <div className="x" onClick={()=> closeModal('phone')}>
                    <X />
            </div>
        </div>
    )
}
export default PhoneKyc