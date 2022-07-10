import { useRouteMatch } from "react-router-dom"

const rewriteUrl = () => {
    if(window.location.href.includes('bluednetwork.com')){
        return `https://api.bluednetwork.com/`
    }
    else return `https://staging-api.bluednetwork.com/`
}
export default rewriteUrl