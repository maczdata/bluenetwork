import Ring from '@bit/joshk.react-spinners-css.ring';
import axios from 'axios';
import { useEffect, useState } from 'react';
import { toast } from 'react-toastify';
import logo from '../../../../assets/logo.svg'
import Success from '../../../../assets/success';
import X from '../../../../assets/x';
import rewriteUrl from '../../../../helper/rewriteUrl';

const MyOffers = ({handleFetchMyOffers, data, loading, setActive}) => {
    const [modalState, setModalState] = useState(0)

    const [idx, setIdx] = useState()

    const [fieldState, setFieldState] = useState({})

    const [formRes, setFormRes] = useState()

    const handleSubmitField = async (formData) => {
        let response
        response = await axios(
            {
                method: 'post',
                url: `${rewriteUrl()}front_api/offers/users/update/fields`,
                params:{
                    user_offer_id: data[idx].id
                },
                data: formData
                
            }
        ).then((response)=> {
            setFormRes(response.data)
            handleFetchMyOffers()
        })
        .catch((error)=> {
            toast.error(error.response.data.message)
            setModalState()
        })
    }
    const [initials, setInitials] = useState();

    useEffect(() => {
        if(data){
        idx && setInitials(data[idx].filled_form)}
    }, [data, idx])

    useEffect(()=>{

        if (idx && initials) {
            const myObject = {}
            for (let i=0; i < data[idx].offer.fields.length; i++){
            console.log('done');
            myObject[data[idx].offer.fields[i].field_name] = initials.map((item)=>{
                if(data[idx].offer.fields[i].id === item.field_id){
                    console.log(item,data[idx].offer.fields[i].id,  'item');
                    return item.filled_field
                }
            }).filter(data => data && data)[0]
            }
            setFieldState(myObject)
        }

    }, [idx, initials])
    console.log(fieldState, 'my fields');
    console.log(initials , 'iiinii')
    return(
        <div className="my_offers offers">
            {
                !data && loading && <Ring />
            }
            {
                !data && !loading && <div className='pendingOffer'>
                    <h4>
                        THERE ARE NO PENDING OFFERS FOR YOU
                    </h4>
                    <button onClick={()=> setActive(1)}>
                        View all offers
                    </button>
                </div>
            }
            {
                data && data.map((offerItem, idx)=>{
                    return <>
                        <div className='before'>
                            <div
                            className={`single__category_web `
                            //   ${showClicked === index && "button__picked"}
                            }
                            //   key={index}
                            >
                            <img src={logo} alt='img' />
            
                            <div className='design__price'>
                                <p>{offerItem?.amount} </p>
                            </div>
                            {offerItem?.status === 'pending' && <div className='choose__btn'>
                                <button
                                onClick={() => {
                                    setModalState(2)
                                    setIdx(idx)
                                    setFormRes()
                                }}
                                >
                                Edit
                                </button>
                            </div>}
                            </div>
                            <div className='variant_content'>
                                <p className='design__header'>{offerItem?.offer?.name}</p>
                                <p className='design__description'>{offerItem?.offer?.description}</p>
                            </div>
                            <p className="status">
                                {('Status: ' + offerItem?.status).toUpperCase()}
                            </p>
                        </div>
                        
                        {/* <div className="offerItem">
                            <h4>
                                {offerItem.offer.name}
                            </h4>
                            <h5>
                                {offerItem.offer.price}
                            </h5>
                            <p>
                                {offerItem.offer.description}
                            </p>
                        </div> */}
                    </>
                })
            }
            {modalState === 2 && <div className="modalContainer">
                <div className="centerModal">
                    <div className="top">
                        <div className="close" onClick={()=> {
                            setModalState()
                            setFormRes()
                            } }>
                            <X />
                        </div>
                    </div>
                    {!formRes && <p>
                        Your offer has been paid for, please fill out the following details.
                    </p>}
                    {!formRes && <form action="" onSubmit={(e)=>{

                        e.preventDefault()

                        const formData = new FormData()

                        for (let i=0; i < data[idx].offer.fields.length; i++){

                            console.log('appended');

                            formData.append(`custom_fields[${data[idx].offer.fields[i].field_name}]`, fieldState[data[idx].offer.fields[i].field_name])
                            
                        }
                        setFormRes('loading')

                        handleSubmitField(formData)
                    }}>
                        {
                            data[idx].offer.fields.map((fieldItem, idt)=>{
                                if(fieldItem.type !== 'File' && fieldItem.type !== 'Select'){
                                    return <label htmlFor="">
                                        <p>
                                            {fieldItem.field_name}
                                        </p>
                                        <input type={fieldItem.type} onChange={(e)=>{
                                            initials && setInitials([
                                                
                                                ...initials.map((initialItem)=>{
                                                    if(initialItem.field_id === fieldItem.id){
                                                        return{
                                                            ...initialItem, filled_field: e.target.value
                                                        }
                                                    }
                                                    else{
                                                        return initialItem
                                                    }
                                                })
                                            ].filter(data => data && data))

                                            setFieldState({...fieldState, [fieldItem.field_name]: e.target.value})

                                            console.log(initials )

                                        }} required value={initials ? initials.map((item, idx)=>{

                                            if(fieldItem.id === item.field_id) return item.filled_field

                                        })[0] : fieldState?.[fieldItem.field_name]}/>
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
                                            
                                            {                                                                                                                   
                                                ans.map((item, idx)=>{
                                                    return <option value={item} selected={initials? initials.map((itemy, idx)=>{
                                                        if(fieldItem.id === itemy.field_id){ 
                                                            if (itemy.filled_field === item) {
                                                                return true
                                                            } else return false
                                                        }
                                                    })[0] : ''}>
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
                                SUCCESSFULLY EDITED
                            </h5>
                        </div>
                    }
                </div>
            </div>}
        </div>
    )
}
export default MyOffers