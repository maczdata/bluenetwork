import React from 'react'


const DynamicFields = ({ type, title, name, required, providerDetails, handleChange, formControl, variantData, answers, isInvalid = false, ...props }) => {
  let fieldToDisplay
  let newAnswers = answers ? JSON.parse(answers) : null
  switch (type) {
    case "select":
      let optionFields
      if (newAnswers) {
        optionFields = newAnswers?.map((value) => (
          <option key={value} value={value}>
            {value}
          </option>
        ))

      } else {
        if (title === "Variant") {
          optionFields = variantData?.map((variant, id) => (
            <option key={id} value={variant.id}>
              {variant.title} - ₦{variant.price}
            </option>
          ))
        } else {
          optionFields = providerDetails &&
            providerDetails?.map((provider, id) => (
              <option key={id} value={provider.id}>
                {provider.title}
              </option>
            ))
        }
      }
      fieldToDisplay = (
        <><label>{title}</label>
          <select
            id={name}
            name={name}
            value={formControl[name]}
            onChange={handleChange}
            className={`mb-3 ${isInvalid ? 'invalid' : ''}`}
            required={required}
          >
            <option value="">{title}</option>
            {optionFields}
          </select>
        </>
      )
      break;
    case "textarea":
      fieldToDisplay = (
        <><label>{title}</label>
          <textarea
            value={formControl[name]}
            onChange={handleChange}
            id={name}
            className={`${isInvalid ? 'invalid' : ''}`}
            name={name}
            placeholder={title}
            required={required}
          />
        </>
      )

      break;
    case "radio":
    case "checkbox":
      fieldToDisplay = (<div className=''>
        {title}
        {newAnswers && newAnswers.map((answer) => (
          <div className=''>
            <input
              id={answer}
              name={name}
              type={type}
              style={{ marginRight: "10px", width: 'max-content' }}
              onChange={handleChange}
              checked={formControl[name] === answer}
              value={answer}
            />
            <label htmlFor={answer} style={{ textTransform: 'capitalize' }}>{answer}</label>
          </div>

        ))}
      </div>
      )
      break;
    case "file":
    case "image":
      fieldToDisplay = (
        <><label>{title}</label>
          <input
            onChange={handleChange}
            id={name}
            className={`${isInvalid ? 'invalid' : ''}`}
            name={name}
            type="file"
            accept={`${type === "image"? 'image/*' : props.accept || ''}`}
            placeholder={title}
            required={required}
            {...props}
          />
        </>
      )
      break;
    case "text":
    default:
      fieldToDisplay = (
        <><label>{title}</label>
          <input
            value={formControl[name]}
            onChange={handleChange}
            id={name}
            className={`${isInvalid ? 'invalid' : ''}`}
            name={name}
            type={type}
            placeholder={title}
            required={required}
            {...props}
          />
        </>
      )
      break;
  }

  return (
    <div className='quick_service_input' key={name}>
      {fieldToDisplay}
    </div>
  )
}

export default DynamicFields;