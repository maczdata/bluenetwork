import { useEffect, useMemo, useState } from 'react'
import './index.scss'
import Select from 'react-select'
import countryList from 'react-select-country-list'
import X from '../../../../assets/x'
import axios from 'axios'
import { useDispatch } from 'react-redux'
import { loadUser, USER_LOADED } from '../../../../redux/actions/auth'
import Ring from '@bit/joshk.react-spinners-css.ring';
import { toast } from 'react-toastify'
import rewriteUrl from '../../../../helper/rewriteUrl'

const Address = ({closeModal}) => {
    const [formData, setFormData] = useState({
    })
    const [formToSubmit, setFormToSubmit] = useState({})
    useEffect(()=>{
        setFormToSubmit({...formToSubmit, 
            fullAddress: `${formData?.residentAddress+ ' '+ formData?.city + ' ' + formData?.country}`})
    }, [formData])
    const options = useMemo(() => countryList().getData(), [])
    const changeHandler = value => {
        setFormData({...formData, country: value.label})
    }
    const handleFileUpload = (e) =>{
        (e.target.files[0].size <= 2048000) ? setFormToSubmit({...formToSubmit, proof_of_address: e.target.files[0]}) : alert('file too large')
    }

    console.log(formData)

    console.log(formToSubmit);

    const [res, setRes] = useState()

    const [err, setErr] = useState()

    const dispatch = useDispatch() 

    const [loading, setLoading] = useState()
    

    const verifyAddress = async (formDataToSubmit) => {
        let response
        
        response = await axios({
            url: `${rewriteUrl()}front_api/verification/proof-of-address`,
            data: formDataToSubmit,
            method: "post",
            params:{
                token: localStorage.getItem('access_token'),
                address: formToSubmit?.fullAddress,
                proof_of_address_type: formToSubmit?.proof_type
            },
        }).then((response)=>{
            setRes(response.data.message)
            dispatch({
                type: USER_LOADED,
                payload: response.data
            })
            dispatch(loadUser())
            
            closeModal('address')
            setLoading(false)
        }).catch((err)=>{
            setErr(err.response.data.message)
            toast.error(err.response.data.message)
            setLoading(false)
        })
    }
    return(
        !res ? (<form action="" onSubmit={(e)=>{
            e.preventDefault()
            const formDataToSubmit = new FormData()
            formDataToSubmit.append('proof_of_address', formToSubmit?.proof_of_address)
            verifyAddress(formDataToSubmit)
        }}>
            <div  className="x">
                <h5>
                    Verify address
                </h5>
                <div className="xclass" onClick={()=> closeModal('address')}>
                    <X />
                </div>
            </div>
            <label htmlFor="">
                <p>
                    Residential address
                </p>
                <input onChange={(e)=>{
                    setFormData({...formData, residentAddress: e.target.value})
                }} type="text" required/>
            </label>
            <label htmlFor="">
                <p>
                    City
                </p>
                <input onChange={(e)=>{
                    setFormData({...formData, city: e.target.value})
                }} type="text" required/>
            </label>
            <label htmlFor="">
                <p>
                    Select country
                </p>
                <Select options={options} value={formData?.country?.label} onChange={changeHandler}/>
            </label>
            <div className="proof">
                <label htmlFor="">
                    <p>
                        Select Proof type
                    </p>
                    <span className='contain'>
                        <input onClick={()=> setFormToSubmit({...formToSubmit, proof_type: 'LATEST ELECTRIC BILL'})} type="radio" className='radio' name="bill" id="" required/><span>LATEST ELECTRICITY BILL</span>
                    </span>
                    <span className='contain'>
                        <input onClick={()=> setFormToSubmit({...formToSubmit, proof_type: 'BANK STATEMENT'})} type="radio" className='radio' name="bill" id="" required/><span>LAST 3 MONTHS BANK STATEMENT</span>
                    </span>
                </label>
            </div>
            <label htmlFor="">
                <p>
                    ADDRESS DOCUMENT
                </p>
                <input accept='.png, .jpeg, .jpg, .svg, .pdf, .doc' onChange={(e)=> handleFileUpload(e)} type="file" pattern='' required/>
            </label>

            {err && (
                <p  className='err'>
                    {err}
                </p>
            )}

            <label htmlFor="">
                <button>
                    Submit
                    <span>{loading && <Ring color={'white'} size={16}/>}</span>
                </button>
            </label>
            

        </form>)
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
export default Address