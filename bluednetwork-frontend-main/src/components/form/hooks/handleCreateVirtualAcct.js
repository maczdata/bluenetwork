import axios from "axios";

const handleCreateVirtualAccountFunct = async (email) =>{
    console.log('sndd', email);
    let response = await axios(
        {
            method: 'post',
            url: 'https://control.bluednetwork.com/front_api/account/create-virtual-account',
            params:{
                email: email
            }
        }
    ).then((response)=>{
        console.log(response, 'resst');
    }).catch((error)=>{
        console.log(error , 'resst');
    })
}
export default handleCreateVirtualAccountFunct 