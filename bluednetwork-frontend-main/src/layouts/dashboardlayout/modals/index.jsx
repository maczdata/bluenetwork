import './index.scss'
const Modal = ({children}) => {
    return(
        <div className="modalForDashBoard">
            <div className="center">
                {children}
            </div>
        </div>
    )
}
export default Modal