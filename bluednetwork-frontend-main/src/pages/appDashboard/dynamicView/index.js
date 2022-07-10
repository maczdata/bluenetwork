import React, { useCallback, useEffect, useMemo, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux';
import { useLocation } from 'react-router-dom'
import { useParams } from 'react-router-dom';
import { Spinner } from 'reactstrap';
import RecentTransactions from '../../../components/RecentTransactions';
import { brandingFeature, getAllServices, getSingleService, getWebPreviewDynamic, VARIANT_FAIL, VARIANT_SUCCESS } from '../../../redux/actions/service';
import WalletBallance from '../dashboardHome/WalletBallance';
import IMG2 from "../../../assets/credit-card.svg";
import Fade from '../../../components/advert';
import WebModal from '../dashboardWeb/WebModal';
import axios from 'axios';
import { loadUser } from '../../../redux/actions/auth';
import DynamicFields from '../../../components/DynamicFields/DynamicFields';
import { toast } from "react-toastify";
import DirectorsModal from './DirectorsModal';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import WebPreviewModal from '../dashboardWeb/WebPreviewModal/Dynamic';
import { formatter } from '../../../components/Helpers';
import rewriteUrl from '../../../helper/rewriteUrl';


export default function DynamicView(props) {
    const token = localStorage.getItem("access_token");
    const config = {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    };

    let { id } = useParams();
    const dispatch = useDispatch();
    const servicesData = useSelector((state) => state.service);
    const service = servicesData?.service?.data;

    const [fields, setFields] = useState([])
    const [addOns, setAddOns] = useState([])
    const [add_ons, setAdd_ons] = useState([]);

    const [fieldsState, setFieldsState] = useState([])
    console.log('the state', fieldsState);
    const [invalidArray, setInvalidArray] = useState([])
    const [directorsArray, setDirectorsArray] = useState([])

    const [files, setFiles] = useState({
        passport: null,
        signature: null,
        validId: null,
    })

    const [modals, setModals] = useState({
        webModal: false,
        directorsModal: false,
        webPreview: false
    })
    const brandingCategoryInfo = servicesData?.variant?.data;
    const allFeaturedata = servicesData?.featureSuccess?.data;

    let defaultState = {
        initFetching: true,
        data: [],
        slug: '',
        loading: false,
        bdBillsIndex: '',
        toggleExchange: false,
        selectedBill: {
            title: ''
        },
        showModal: false,
        addOns: [],
        serviceType: "",
        amountToPay: 0
    }
    const [state, setState] = useState({
        ...defaultState
    })

    const [selectedCategory, setSelectedCategory] = useState({})

    const updateState = (data) => {
        setState((prev) => ({
            ...prev,
            ...data
        }))
    }

    const handleFileUploads = ({ target: { name, files } }) => {
        if (invalidArray.includes(name)) {
            let newArr = []
            invalidArray.forEach((param) => {
                if (param !== name) {
                    newArr.push(param)
                }
            })
            setInvalidArray(newArr)
        }
        setFiles((prev) => ({
            ...prev,
            [name]: files && files[0] ? files[0] : null
        }))
    }
    const reloadFields = useCallback(async () => {
        let fieldsObj = {}
        await fields.forEach((field) => {
            fieldsObj[field.key] = ""
        })
        setFieldsState(fieldsObj)
    }, [fields])

    const handleCategorySelect = (data) => {
        setSelectedCategory({ ...data })
        setFields(data.custom_fields || [])
        setAddOns(data.addons || [])
        dispatch(brandingFeature(data.id));

    }
    const toggleModal = (data) => {
        setModals((prev) => ({
            ...prev,
            ...data
        }))
    }
    const getServiceData = useCallback(async () => {
        if (service) {
            let serviceData = service.filter((a) => a.title.replace(" ", "_").toLowerCase() === id)[0]
            let serviceId = serviceData?.id
            try {
                // updateState({ initFetching: true })
                let { data } = await getSingleService(serviceId)
                // updateState({
                setState((prev) => ({
                    ...prev,
                    initFetching: false,
                    data: data?.data[0]?.services,
                    slug: serviceData.slug,
                    bdBillsIndex: data.data[0]?.services[0]?.key,
                    serviceType: data?.data[0]?.services[0]?.key,
                    selectedBill: {
                        ...data.data[0]
                    }

                }))
            } catch (error) {
                setState((prev) => ({
                    ...prev,
                    initFetching: false,
                    data: []
                }))
            }
        }

    }, [id, service])
    const cleanUpFn = useCallback((initFetching = true) => {
        setAddOns([])
        setFields([])
        setSelectedCategory({})
        setDirectorsArray([])
        setModals({
            webModal: false

        })
        setState((prev) => ({
            ...prev,
            ...defaultState,
            initFetching: initFetching
        }))
    }, [])
    useEffect(() => {
        getServiceData()

        // cleanup
        return () => {
            cleanUpFn()
        }
    }, [getServiceData, cleanUpFn])


    const calcAmountToPay = useCallback(() => {
        let newAddons = []
        let addOnAmt = 0
        for (let i = 0; i < add_ons.length; i++) {
            const element = Object.values(add_ons[i])[0];
            for (let j = 0; j < addOns.length; j++) {
                const parentElement = addOns[j];

                if (element == parentElement.id) {
                    newAddons.push(parentElement)
                    addOnAmt += Number(parentElement.price)
                }
            }

        }
        setState((prev) => ({
            ...prev,
            // loading: true,
            addOns: newAddons,
            amountToPay: (Number(selectedCategory.price) + addOnAmt)
        }))
    }, [add_ons, addOns, selectedCategory])
    const getVariants = useCallback(async (key) => {
        try {
            let url = `${rewriteUrl()}front_api/services?filter[key]=${key}&include=variants.customFields,variants.addons,customFields,addons`
            const res = await axios.get(
                // `${rewriteUrl()}front_api/service/single/${key}/variants`,
                url,
                config
            );
            dispatch({
                type: VARIANT_SUCCESS,
                payload: { data: res?.data?.data[0].variants || [], message: "Successful" },
            });
        } catch (error) {
            dispatch({
                type: VARIANT_FAIL,
            });
            return error;
        }
    }, [dispatch])
    useEffect(() => {
        reloadFields()
    }, [reloadFields])
    useEffect(() => {
        dispatch(loadUser());
        dispatch(getAllServices());
        // dispatch(brandingCategory(serviceType));
        if (state.serviceType !== "") {
            getVariants(state.serviceType);
        }
    }, [state.serviceType, dispatch, getVariants]);

    const getAddons = (id) => {
        const filteredCategory =
            brandingCategoryInfo &&
            brandingCategoryInfo.find((data) => {
                if (data.id == id) {
                    return data;
                }
            });

        updateState({ addOns: filteredCategory.addons });
    };


    const handleSubmit = (e) => {
        e.preventDefault()
        let valuesArray = Object.values(fieldsState)
        let keysArray = Object.keys(fieldsState)

        let payload = {}
        payload.service_key = state.serviceType
        payload.service_type_slug = state.slug
        payload.addons = add_ons
        payload.variant_key = selectedCategory.key
        payload.mode_of_payment = "wallet"
        let custom_fields = {}
        for (let i = 0; i < keysArray.length; i++) {
            custom_fields[`${keysArray[i]}`] = valuesArray[i]
        }
        payload.custom_fields = custom_fields
        // let newAddons = []
        // let addOnAmt = 0
        // for (let i = 0; i < add_ons.length; i++) {
        //     const element = Object.values(add_ons[i])[0];
        //     for (let j = 0; j < addOns.length; j++) {
        //         const parentElement = addOns[j];

        //         if(element == parentElement.id){
        //             newAddons.push(parentElement)
        //             addOnAmt += Number(parentElement.price)
        //         }
        //     }

        // }
        // updateState({
        //     // loading: true,
        //     addOns: newAddons,
        //     amountToPay: (Number(selectedCategory.price) + addOnAmt)
        // })
        // calcAmountToPay()
        // dispatch(
        //     getWebPreviewDynamic(payload, () => toggleModal({ webPreview: true }), () =>
        //         updateState({
        //             loading: false
        //         }))
        // );
        toggleModal({ webPreview: true })
    }

    useEffect(() => {
        calcAmountToPay()
    }, [calcAmountToPay])
    const handlePaymentSuccess = async (e) => {
        e.preventDefault()
        let valuesArray = Object.values(fieldsState)
        let keysArray = Object.keys(fieldsState)
        let invalidFields = []
        for (let i = 0; i < fields.length; i++) {
            const element = fields[i];
            if (element.key === keysArray[i]) {
                if (element.required === 1 && (!valuesArray[i] || (typeof valuesArray[i] === 'string' && valuesArray[i]?.trim() === ''))) {
                    invalidFields.push(keysArray[i])
                }
            }
        }
        if (selectedCategory?.key === "business-name") {
            let filesValues = Object.values(files)
            let filesKeys = Object.keys(files)
            for (let i = 0; i < filesValues.length; i++) {
                if (!filesValues[i] || filesValues[i] === null) {
                    invalidFields.push(filesKeys[i])
                }
            }
        }
        if (invalidFields.length > 0) {
            setInvalidArray(invalidFields)
        } else {
            setState((prev) => ({
                ...prev,
                loading: true
            }))
            setInvalidArray([])
            //   setShowConfirmModal(true);
            let dataToSend = new FormData()
            dataToSend.append("service_key", state.serviceType)
            dataToSend.append("service_type_slug", state.slug)
            dataToSend.append("addons", JSON.stringify(add_ons))
            dataToSend.append("variant_key", selectedCategory.key)
            dataToSend.append("mode_of_payment", "wallet")
            for (let i = 0; i < keysArray.length; i++) {
                dataToSend.append(`custom_fields[${keysArray[i]}]`, valuesArray[i])
            }
            if (selectedBill?.key === "cac_registration" && selectedCategory?.key !== "business-name") {
                let newDirectorArray = await directorsArray.map((director) => {
                    let dataToSend = new FormData()
                    for (var key in director) {
                        dataToSend.append(key, director[key]);
                    }
                    return dataToSend
                })
                // dataToSend.append("directors", JSON.stringify(directorsArray))
                dataToSend.append("directors", JSON.stringify(newDirectorArray))
            } else if (selectedCategory?.key === "business-name") {
                dataToSend.append("passport", files.passport)
                dataToSend.append("signature", files.signature)
                dataToSend.append("valid_id", files.validId)
            }
            try {
                let { data } = await axios.post(`${rewriteUrl()}front_api/service/order/create`, dataToSend, config)
                toast.success(data?.message || "Successful")
                setState((prev) => ({
                    ...defaultState,
                    initFetching: false,
                    data: prev.data,
                    bdBillsIndex: prev.bdBillsIndex,
                    selectedBill: prev.selectedBill,
                    serviceType: prev.serviceType,
                    slug: prev.slug
                }))
                dispatch(loadUser())
                setDirectorsArray([])
                setModals({
                    webModal: false,
                    directorsModal: false,
                    webPreview: false
                })
                reloadFields()
            } catch (error) {
                toast.error(error?.response?.data?.message || "An error occured")
                setState((prev) => ({
                    ...prev,
                    loading: false
                }))
            }
        }
    }

    const handleAdd_ons = (e) => {
        const name = e.target.name;
        const value = e.target.value;
        const alreadyExist = add_ons.filter(
            (item) => Object.keys(item)[0] === name
        );
        const oldInfoWithoutTheNewOne = add_ons.filter(
            (item) => Object.keys(item)[0] !== name
        );

        if (alreadyExist.length > 0)
            return setAdd_ons([...oldInfoWithoutTheNewOne]);

        return setAdd_ons([...add_ons, { [name]: value }]);
    };


    // const getFieldsAndVariants = async (id)=>{
    //     try {
    //         let url = `${rewriteUrl()}front_api/services?filter[id]=${id}&include=customFields,variants.addons,variants.customFields`
    //         let {data} = await axios.get(url,config)
    //     } catch (error) {
    //         console.log(error)
    //     }
    // }

    const handleChange = ({ target: { name, value, files, type } }) => {
        if (invalidArray.includes(name)) {
            let newArr = []
            invalidArray.forEach((param) => {
                if (param !== name) {
                    newArr.push(param)
                }
            })
            setInvalidArray(newArr)
        }
        setFieldsState((prev) => ({
            ...prev,
            [name]: type === "file" ? files[0] : value
        }))
    }

    const deleteDirector = (email) => {
        let newDirector = []
        for (let i = 0; i < directorsArray.length; i++) {
            const element = directorsArray[i];
            if (element.email !== email) {
                newDirector.push(element)
            }
        }

        setDirectorsArray(newDirector)
    }

    if (state.initFetching === true)
        return <div className="d-flex align-items-center justify-content-center vh-100"> <Spinner size="xl" /></div>

    let { data, bdBillsIndex, toggleExchange, loading, selectedBill: { title, ...selectedBill } } = state
    let { webModal } = modals
    return (
        <div>
            {webModal && (
                <WebModal
                    data={brandingCategoryInfo}
                    closeGiftCardModal={() => toggleModal({ webModal: false })}
                    handleCategorySelect={handleCategorySelect}
                    handleSelectedDetails={() => setState((prev) => ({
                        ...prev, toggleExchange: true
                    }))}
                    selectedCategory={selectedCategory}
                />
            )}
            
            {modals.webPreview && (
                <WebPreviewModal
                    handlePaymentSuccess={handlePaymentSuccess}
                    closePreviewModal={() => {
                        toggleModal({ webPreview: false })
                    }}
                    loading={loading}
                    selectedCategory={selectedCategory}
                    fields={fieldsState}
                    addons={state.addOns}
                    amount_to_pay={state.amountToPay}
                // showWalletButton={showWalletButton}
                />
            )}
            <div className='barner__container mt-3'>
                <Fade />
            </div>
            <div className='display_on_small_screen_branding'>
                <WalletBallance />
            </div>
            <div className='first__section'>
                <div className='web_buttons'>
                    <div className="buttons">
                        {data &&
                            data.map((brand, index) => (
                                <React.Fragment
                                    key={index}
                                >
                                    {(brand.enabled === 1 || brand.enabled === true) && <button
                                        className={
                                            bdBillsIndex === brand.key ? "bill-tab-active" : "bill-tab"
                                        }
                                        onClick={() => {
                                            setState((prev)=>({
                                                ...prev,
                                                bdBillsIndex: brand.key,
                                                serviceType: brand.key,
                                                toggleExchange: false,
                                                selectedBill: {
                                                    ...brand
                                                }
                                            }));
                                        }}
                                    >
                                        {brand.title}
                                    </button>}
                                </React.Fragment>
                            ))}

                    </div>
                    {!toggleExchange ?
                        <div className='web_category_selection '>
                            <h4 className='web_header'>{title}</h4>
                            <p className='select_web_supplier'>
                                Select a category to get started.
                            </p>
                            <div className='choose__modalbtn'>
                                <button
                                    className='choose__modal__btn'
                                    onClick={() => toggleModal({ webModal: true })}
                                >
                                    Choose Category
                                </button>
                            </div>
                        </div>
                        :
                        <form onSubmit={handleSubmit}>
                            <div className='categories__details'>
                                <div className='first__details'>
                                    <div className='web_category_selection '>
                                        <div className='choose__modalbtns'>
                                            <img src={selectedCategory.icon || selectedCategory?.service_variant_icon} alt='' />
                                            <button
                                                className='choose__modal__btns'
                                                onClick={(e) => {
                                                    e.preventDefault()
                                                    // setShowWebPreviewModal(false);
                                                    toggleModal({ webModal: true })
                                                    // setWebCatModal(true);
                                                }}
                                            >
                                                Choose Category
                                            </button>
                                        </div>
                                    </div>

                                    <div className='features__wrapper'>
                                        {allFeaturedata?.map((data) => (
                                            <>
                                                <p>{data.title}</p>
                                                <div className='features__container'>
                                                    {data?.featurize_values?.map((item) => (
                                                        <div className='m-2'>
                                                            <label className='mx-2'>{item?.title}</label>
                                                            <input
                                                                type='radio'
                                                                name={data.slug}
                                                                value={item.id}
                                                            // onChange={(e) => handleChange(e)}
                                                            />
                                                        </div>
                                                    ))}
                                                </div>
                                            </>
                                        ))}
                                    </div>
                                    <div className='addons__wrapper'>
                                        {addOns.length > 0 &&
                                            <>
                                                <h2>Addons</h2>
                                                <p>
                                                    Select extra exciting fearures you would like to add
                                                    to your order.
                                                </p>
                                            </>}
                                        {addOns?.map((data) => (
                                            <>
                                                <div className='single__addons'>
                                                    <label>{data?.title}</label>
                                                    <input
                                                        type='checkbox'
                                                        name={data.slug}
                                                        value={data.id}
                                                        onChange={(e) => handleAdd_ons(e)}
                                                    />
                                                    <p className='single__price'>
                                                        {data?.formatted_price || `₦${formatter.format(data?.price)}`}
                                                    </p>
                                                    <p className='single__desc'>{data?.description}</p>
                                                </div>
                                            </>
                                        ))}
                                    </div>
                                    <div>
                                        {(selectedBill?.key === "cac_registration" && selectedCategory?.key !== "business-name") &&

                                            <>
                                                <button type='button' onClick={() => toggleModal({ directorsModal: !modals.directorsModal })}> Add Directors</button>
                                                {directorsArray.map((director, i) => (
                                                    <div key={director.email} style={{
                                                        display: 'flex'
                                                    }}>
                                                        <div>
                                                            <p>Fullname: {director.full_name}</p>
                                                            <p>Designation: {director.designation}</p>
                                                            <p>Email: {director.email}</p>

                                                        </div>
                                                        <div>
                                                            <button onClick={() => deleteDirector(director.email)} style={{
                                                                background: 'red',
                                                                color: 'white'
                                                            }}>Delete </button>
                                                        </div>
                                                    </div>
                                                ))}
                                            </>
                                        }
                                    </div>
                                    <div className='payments'>
                                        <span className='payments__text'>You will pay</span>
                                        <span className='payments__amount'>₦{formatter.format(state.amountToPay)}</span>
                                    </div>
                                </div>

                                <div className='second__details'>
                                    {/* To Display the form fields here */}
                                    {fields && fields.map((field, i) => (
                                        <DynamicFields
                                            {...{ ...field, type: field.key === "airtime_amount_transferred" ? 'number' : field.type, min: field.key === "airtime_amount_transferred" ? 1000 : 0 }}
                                            name={field.key}
                                            providerDetails={[]}
                                            isInvalid={invalidArray.includes(field.key)}
                                            variantData={[]}
                                            invalidArray={invalidArray}
                                            formControl={fieldsState}
                                            handleChange={handleChange} />
                                    ))}
                                    {selectedBill?.key === "cac_registration" && <>
                                        {selectedCategory?.key === "business-name" && <>

                                            <div className='quick_service_input'>
                                                <label>Passport Photograph</label>
                                                <input
                                                    type='file'
                                                    name="passport"
                                                    className={`${invalidArray.includes("passport") ? 'invalid' : ''}`}
                                                    onChange={handleFileUploads}
                                                    required
                                                />
                                            </div>

                                            <div className='quick_service_input'>
                                                <label>Signature</label>
                                                <input
                                                    type='file'
                                                    name="signature"
                                                    className={`${invalidArray.includes("signature") ? 'invalid' : ''}`}
                                                    onChange={handleFileUploads}
                                                    required
                                                />
                                            </div>
                                            <div className='quick_service_input'>
                                                <label>Valid ID</label>
                                                <input
                                                    className={`${invalidArray.includes("validId") ? 'invalid' : ''}`}
                                                    type='file'
                                                    name="validId"
                                                    onChange={handleFileUploads}
                                                    required
                                                />
                                            </div>
                                        </>}
                                    </>}
                                    <button type='submit' disabled={loading}> {loading ?
                                        <Spinner color='light' />
                                        : "Continue"}</button>
                                </div>
                            </div>
                        </form>

                    }
                    <DirectorsModal
                        modalIsOpen={modals.directorsModal}
                        closeModal={() => toggleModal({ directorsModal: !modals.directorsModal })}
                        setDirectorsArray={setDirectorsArray}
                        directorsArray={directorsArray}
                    />
                </div>


                <div className='wallet__information'>
                    <div className='hide_on_small_screen'>
                        <WalletBallance />
                    </div>
                    <div className='recent__transaction'>
                        <div className='first'>
                            <span>
                                <img src={IMG2} alt='' />
                            </span>
                            <p>Recent Transactions</p>
                        </div>

                        <div className='second'>
                            <RecentTransactions />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    )
}
