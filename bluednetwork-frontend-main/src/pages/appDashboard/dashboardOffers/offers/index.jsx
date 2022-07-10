import Ring from '@bit/joshk.react-spinners-css.ring';
import axios from 'axios';
import { method } from 'lodash';
import { useState } from 'react';
import { useSelector } from 'react-redux';
import { toast, ToastContainer } from 'react-toastify';
import X from '../../../../assets/x';
import 'react-toastify/dist/ReactToastify.css';
import { Input } from 'reactstrap';
import Success from '../../../../assets/success';
import logo from '../../../../assets/logo.svg'
import rewriteUrl from '../../../../helper/rewriteUrl';
const Offers = ({data, loading}) => {

    const [modalState, setModalState] = useState(0)

    const [idx, setIdx] = useState()
    
    const [res, setRes] = useState()

    const [formRes, setFormRes] = useState()

    const [err, setErr] = useState()

    const [fieldState, setFieldState] = useState({})

    const currentUser = useSelector( (state) => state.auth.data)

    console.log('err', err);

    const handlePay = async () => {
        let response
        response = await axios(
            {
                url: `${rewriteUrl()}front_api/offers/pay`,
                method: 'post',
                params: {
                    user_id: currentUser?.id,
                    offer_id: data[idx].id
                }
            }
        ).then((response)=>{
            setRes(response)
            setModalState(2)
        }).catch((error)=>{
            setErr(error.response.data.message)
            toast(error?.response?.data?.message)
        })
    }

    const handleSubmitField = async (formData) => {
        let response
        response = await axios(
            {
                method: 'post',
                url: `${rewriteUrl()}front_api/offers/users/fill/fields`,
                params:{
                    user_offer_id: res?.data?.data?.id
                },
                data: formData
                
            }
        ).then((response)=> {
            setFormRes(response.data)
        })
        .catch((error)=> {
            toast(error.response.data.message)
            setModalState()
        })
    }
    console.log(formRes, 'ressy');
    console.log(data, 'rest');
    console.log(fieldState, 'idx');
    
    return(
        <div className="offers">
            {
                data && data.map((offerItem, idx)=>{
                    return <> <div className='before'>
                    <div
                      className={`single__category_web `
                    //   ${showClicked === index && "button__picked"}
                    }
                    //   key={index}
                    >
                      <img src={logo} alt='img' />
    
                      <div className='design__price'>
                        <p>{offerItem.price} {console.log("webbb", offerItem)}</p>
                      </div>
                      <div className='choose__btn'>
                        <button
                          onClick={() => {
                            setModalState(1)
                            setIdx(idx)
                          }}
                        >
                          View full
                        </button>
                      </div>
                    </div>
                    <div className='variant_content'>
                      <p className='design__header'>{offerItem?.name}</p>
                      <p className='design__description'>{offerItem?.description}</p>
                    </div>
                  </div>
                    {/* <div className="offerItem">
                        <h4>
                            {offerItem.name}
                        </h4>
                        <h5>
                            {offerItem.price}
                        </h5>
                        <p>
                            {offerItem.description}
                        </p>
                        <button onClick={()=> {
                            setModalState(1)
                            setIdx(idx)
                        }}>
                            View full
                        </button>
                    </div> */}
                    </>
                })
            }
            {
                !data && loading && <Ring color={'#000152'}/>
            }
            {
                !data  && !loading && <h4 className='pendingOffer'>
                    THERE ARE NO AVAILABLE OFFERS
                </h4>
            }
            {
                modalState ===  1 && <div className="modalContainer">
                    <div className="centerModal">
                        <div className="top">
                            <h4>
                                {data[idx].name}
                            </h4>
                            <div className="close" onClick={()=> setModalState()}>
                                <X />
                            </div>
                        </div>
                        <p>
                            {data[idx].description}
                        </p>

                        <h5 className='tit'>
                            SERVICES
                        </h5>
                        {
                            data[idx]?.services.map((serviceItem)=>{
                                return <div className="modalItem">
                                    
                                    <h5>
                                        {serviceItem.name}
                                    </h5>
                                    <p>
                                        {serviceItem.description}
                                    </p>
                                    
                                </div>
                            })
                        }
                        <button onClick={()=>{
                            handlePay()
                            setRes('loading')
                            setFormRes()
                        }}>
                            Pay {data[idx].price} {res === 'loading' && <Ring size={15} color={'white'}/>}
                        </button>
                        <p style={{maxWidth: 350, marginTop: 20, color: 'red'}}>
                            {err}
                        </p>
                    </div>
                </div>
            }
            {
                modalState === 2 &&  (
                    <div className="modalContainer">
                        <div className="centerModal">
                            <div className="top">
                                <div className="close" onClick={()=> setModalState() }>
                                    <X />
                                </div>
                            </div>
                            {!formRes && <p>
                                Your offer has been paid for, please fill out the following details.
                            </p>}
                            {!formRes && <form action="" onSubmit={(e)=>{
                                e.preventDefault()
                                const formData = new FormData()
                                for (let i=0; i < data[idx].fields.length; i++){
                                    console.log('appended');
                                    formData.append(`custom_fields[${data[idx].fields[i].field_name}]`, fieldState[data[idx].fields[i].field_name])
                                    
                                }
                                setFormRes('loading')
                                handleSubmitField(formData)
                            }}>
                                {
                                    data[idx].fields.map((fieldItem)=>{
                                        if(fieldItem.type !== 'File' && fieldItem.type !== 'Select'){
                                            return <label htmlFor="">
                                                <p>
                                                    {fieldItem.field_name}
                                                </p>
                                                <input type={fieldItem.type} onChange={(e)=>{
                                                    setFieldState({...fieldState, [fieldItem.field_name]: e.target.value})
                                                }} required/>
                                            </label>
                                        }
                                        else if(fieldItem.type === 'File'){
                                            return <label htmlFor="">
                                                <p>
                                                    {fieldItem.field_name}
                                                </p>
                                                <input type={fieldItem.type} onChange={(e)=>{
                                                    setFieldState({...fieldState, [fieldItem.field_name]: e.target.files[0]})
                                                }} required/>
                                            </label>
                                        }
                                        else if(fieldItem.type === 'Select'){
                                            const ans = JSON.parse(fieldItem.answers)
                                            console.log(ans);
                                            return <label htmlFor="">
                                                <p>
                                                    {fieldItem.field_name}
                                                </p>
                                                <select required onChange={(e)=> setFieldState({...fieldState, [fieldItem.field_name]: e.target.value})} name="" id="">
                                                    <option value="">Select</option>
                                                    {
                                                        ans.map((item, idx)=>{
                                                            return <option value={item}>
                                                                {item}
                                                            </option>
                                                        })
                                                    }
                                                </select>
                                            </label> 
                                        }
                                    })
                                }
                                <button>
                                    Submit {formRes === 'loading' && <Ring />}
                                </button>
                            </form>}
                            {
                                formRes !== 'loading' &&  formRes && <div className="successDiv">
                                    <Success width={39} height={39}/>
                                    <h5>
                                        SUCCESSFULLY PAID
                                    </h5>
                                </div>
                            }
                        </div>
                    </div>
                )
            }
        </div>
    )
}
export default Offers