import axios from 'axios'
import { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { toast } from 'react-toastify'
import X from '../../../../assets/x'
import Ring from '@bit/joshk.react-spinners-css.ring';
import './index.scss'
import { loadUser } from '../../../../redux/actions/auth'
import rewriteUrl from '../../../../helper/rewriteUrl'
const EmailKyc = ({closeModal}) => {
    const [err, setErr] = useState()

    const [res, setRes] = useState()

    let response

    const dispatch = useDispatch()

    const currentUser = useSelector( (state) => state.auth.data)

    const resendEmail = async () => {
        response = await axios.post(`${rewriteUrl()}front_api/verification/resend`, null, {
            params: {
                source: formData.source,
                source_value: formData.source_value,
                user_id: currentUser?.id
            }
        }).then((response)=>{
            setRes(response)
            dispatch(loadUser())
        }).catch((err)=>{
            console.log('err', err.response)
            toast.error(err?.response?.data?.message)
            setErr(err.response.data.message)
        })
    }
    console.log('res', res)

    const [formData, setFormData] = useState()

    const [loading, setLoading] = useState()

    useEffect(()=>{

        setFormData({
            ...formData,
            source: 'email',
            source_value: currentUser?.email
        })

    }, [])

    useEffect(()=>{
        if(err) {
            toast.error(err)
            setLoading(false)
        }
        if(res) setLoading(false)
    }, [err, res])

    return(
        !res?
        (
            <form onSubmit={(e)=>{
                e.preventDefault()
                resendEmail()
                setLoading(true)
            }} action="">
                <div className="x" >
                    <h5>
                        Verfy Email
                    </h5>
                    <div className="xclass" onClick={()=> closeModal('email')}>
                        <X />
                    </div>
                </div>
                <p>
                    We have sent an email to  {formData?.source_value}, please check 
                    your spam if you don’t see the mail in your inbox.
                </p>
                <label htmlFor="">
                    <button>Send verification email {loading && <Ring color={'white'} size={16}/>}</button>
                </label>
                <p className="err">
                    {err && err}
                </p>
                
            </form>
        )
        :
        
            <div className='success_screen'>
                <p>
                    We have sent an email to  {formData?.source_value}, please check 
                    your spam if you don’t see the mail in your inbox.
                </p>
                <p>
                    If you want to switch your email address, please do so in 
                    your account settings and you will automatically recieve 
                    an email verification link.
                </p>
                
                <div className="x" onClick={()=> closeModal('email')}>
                    <X />
                </div>
            </div>
    )
}
export default EmailKyc