import axios from 'axios'
import { useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import X from '../../../../assets/x'
import { loadUser, USER_LOADED } from '../../../../redux/actions/auth'
import './index.scss'
import Ring from '@bit/joshk.react-spinners-css.ring';
import rewriteUrl from '../../../../helper/rewriteUrl'


const IdKyc = ({closeModal}) =>{
   const dispatch = useDispatch()

    const [form, setForm] = useState({})
    const [res, setRes] = useState()
    const [err, setErr] = useState()
    const handleFileUpload = (e, type) =>{
        (e.target.files[0].size <= 2048000) ? setForm({...form, [type]: e.target.files[0]}) : alert('file too large')
    }
    console.log(form);
    const verifyId = async (formData) => {
        let response
        response = await axios({
            url: `${rewriteUrl()}front_api/verification/proof-of-identity`,
            data: formData,
            method: 'post',
            params:{
                token: localStorage.getItem('access_token'),
                date_of_birth: form?.date_of_birth,
                proof_of_identity_type: form?.proof_of_identity_type,
                proof_of_identity_number: form?.proof_of_identity_number
            }
            
        }).then((response)=>{
            setRes(response.data.message)
            dispatch({
                type: USER_LOADED,
                payload: response.data
            })
            closeModal('id')
            dispatch(loadUser())
        }).catch((error)=>{
            setErr(error?.response?.data.message)
        })
    }
    console.log('res', res);
    return(
        !res ? <form action="" onSubmit={(e)=>{
            e.preventDefault()
            const formData = new FormData()
            formData.append('proof_of_identity_front', form.proof_of_identity_front)
            formData.append('proof_of_identity_back', form.proof_of_identity_back)
            formData.append('passport_photograph', form.passport_photograph)
            verifyId(formData)
        }}>
            <div  className="x">
                <h5>
                    Verify Identity
                </h5>
                <div className="xclass" onClick={()=> closeModal('id')}>
                    <X />
                </div>
            </div>
            <div className="proof">
                <label htmlFor="">
                    <p>
                        Select Proof type
                    </p>
                    <span className='contain'>
                        <input type="radio" className='radio' name="bill" id="" onClick={(e)=>{
                            setForm({...form, proof_of_identity_type: 'NATIONAL ID'})
                        }} required/><span>NATIONAL ID CARD/SLIP</span>
                    </span>
                    <span className='contain'>
                        <input type="radio" className='radio' name="bill" id="" onClick={(e)=>{
                            setForm({...form, proof_of_identity_type: 'PERMANENT VOTERS CARD'})
                        }} required/><span>PERMANENT VOTER'S CARD</span>
                    </span>
                    <span className='contain'>
                        <input type="radio" className='radio' name="bill" id="" onClick={(e)=>{
                            setForm({...form, proof_of_identity_type: 'DRIVERS LICENSE'})
                        }} required/><span>DRIVER'S LICENSE</span>
                    </span>
                    <span className='contain'>
                        <input type="radio" className='radio' name="bill" id="" onClick={(e)=>{
                            setForm({...form, proof_of_identity_type: 'INTERNATIONAL PASSPORT'})
                        }} required/><span>INTERNATIONAL PASSPORT</span>
                    </span>
                </label>
            </div>
            <label htmlFor="">
                <p>
                    ID number
                </p>
                <input type="text" onChange={(e)=> setForm({...form, proof_of_identity_number: e.target.value})}/>
            </label>
            <label htmlFor="">
                <p>
                    Select Date of birth
                </p>
                <input type="date" name="" id="" onChange={(e) => setForm({...form, date_of_birth: e.target.value})} required/>
            </label>
            <label htmlFor="">
                <p>
                    ID DOCUMENT (FRONT PAGE)
                </p>
                <input type="file" accept='.png, .jpeg, .jpg, .svg, .pdf, .doc' onChange={(e)=> handleFileUpload(e, 'proof_of_identity_front')}/>
            </label>
            <label htmlFor="">
                <p>
                    ID DOCUMENT (BACK PAGE)
                </p>
                <input type="file" pattern='' accept='.png, .jpeg, .jpg, .svg, .pdf, .doc' onChange={(e)=> handleFileUpload(e, 'proof_of_identity_back')}/>
            </label>
            <label htmlFor="">
                <p>
                    Passport
                </p>
                <input type="file" pattern='' accept='.png, .jpeg, .jpg, .svg, .pdf, .doc' onChange={(e)=> handleFileUpload(e, 'passport_photograph')}/>
            </label>
            {
                err && (
                    <p className='err'>{err}</p>
                )
            }
            <label htmlFor="">
                <button onClick={()=>setRes('loading')}>
                    Submit
                    <span>{res === 'loading' && !err && form.length === 6 && <Ring color={'white'} size={14}/>}</span>
                </button>
            </label>
            

        </form>
        :
        <div className="success_screen">
            <p>
                {
                    res
                }
            </p>
            <div className="x" onClick={()=> closeModal('address')}>
                <X />
            </div>
        </div>
    )
}
export default IdKyc