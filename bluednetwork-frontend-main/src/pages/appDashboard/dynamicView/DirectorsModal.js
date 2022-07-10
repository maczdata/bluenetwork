import React, { useState } from 'react'
import Modal from "react-modal";
const customStyles = {
    content: {
        align: "center",
        top: "50%",
        left: "50%",
        right: "auto",
        bottom: "auto",
        marginRight: "-50%",
        transform: "translate(-50%, -50%)",
        width: "30%",
    },
};
export default function DirectorsModal({ modalIsOpen, closeModal, setDirectorsArray, directorsArray }) {
    const defaultState = {
        designation: "",
        full_name: "",
        email: "",
        phone_number: "",
        address: "",
        passport: "",
        valid_id: "",
        signature: "",
    }
    const [state, setState] = useState({
        ...defaultState
    })
    const [errors, setErrors] = useState({
        ...defaultState
    })

    const [invalidArray, setInvalidArray] = useState([])

    const handleChange = ({ target: { value, name, type, files } }) => {
        if (invalidArray.includes(name)) {
            let newArr = []
            invalidArray.forEach((param) => {
                if (param !== name) {
                    newArr.push(param)
                }
            })
            setInvalidArray(newArr)
        }
        setState((prev) => ({
            ...prev,
            [name]: type === "file" ? files[0] : value
        }))
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        let validate = validateFields()
        if (!validate) {
            let newArray = directorsArray.map((director) => director.email)
            if (newArray.includes(state.email)) {
                setErrors((prev) => ({
                    ...prev,
                    email: 'Email already exists'
                }))
            } else {
                setInvalidArray([])
                setDirectorsArray((prev) => ([...prev, state]))
                closeModal()
                setState({
                    ...defaultState
                })
                setErrors({
                    ...defaultState
                })
            }
        }
    }

    const validateFields = () => {
        let keys = Object.keys(state)
        let values = Object.values(state)
        let invalid = []
        for (let i = 0; i < values.length; i++) {
            if (!values[i] || values[i] === "") {
                invalid.push(keys[i])
            }
        }
        setInvalidArray(invalid)
        return invalid.length > 0
    }
    return (
        <Modal
            isOpen={modalIsOpen}
            onRequestClose={closeModal}
            style={customStyles}
            overlayClassName="modal-overlay"
            contentLabel="Example Modal"
            ariaHideApp={false}
        >
            <div className="first__section" style={{ width: '100%', display: 'block' }}>

                <form onSubmit={handleSubmit}>
                    <div className='categories__details' style={{ width: '100%', display: 'block' }}>
                        <div className='second__details'>

                            {["designation", "full_name", "email", "phone_number", "address"].map((field, i) => (
                                <div className='quick_service_input' key={i}>
                                    <input
                                        type='text'
                                        name={`${field}`}
                                        placeholder={`${field.replace(/_/g, " ").toUpperCase()}`}
                                        className={`${invalidArray.includes(`${field}`) ? 'invalid' : ''}`}
                                        onChange={handleChange}
                                        required
                                        style={{ margin: '5px' }}
                                    />
                                    {errors[field] !== "" && <small style={{ color: 'red' }}>{errors[field]}</small>}
                                </div>
                            ))}
                            <div className='quick_service_input'>
                                <label>Passport Photograph</label>
                                <input
                                    type='file'
                                    name="passport"
                                    className={`${invalidArray.includes("passport") ? 'invalid' : ''}`}
                                    onChange={handleChange}
                                    required
                                    style={{ margin: '5px' }}
                                />
                            </div>

                            <div className='quick_service_input'>
                                <label>Signature</label>
                                <input
                                    type='file'
                                    name="signature"
                                    className={`${invalidArray.includes("signature") ? 'invalid' : ''}`}
                                    onChange={handleChange}
                                    required
                                    style={{ margin: '5px' }}
                                />
                            </div>
                            <div className='quick_service_input'>
                                <label>Valid ID</label>
                                <input
                                    className={`${invalidArray.includes("valid_id") ? 'invalid' : ''}`}
                                    type='file'
                                    name="valid_id"
                                    onChange={handleChange}
                                    required
                                    style={{ margin: '5px' }}
                                />
                            </div>
                            <button type='submit'>Add Director</button>

                        </div>
                    </div>
                </form>
            </div>
        </Modal>
    )
}
