import {useContext, createContext, useEffect, useState} from 'react'

import {
    GoogleAuthProvider,
    signInWithPopup, 
    signInWithRedirect, 
    signOut,
    onAuthStateChanged,
    getRedirectResult
} from "firebase/auth";

import { auth } from '../firebase';
import { toast } from 'react-toastify';
import { socialMediaAuth } from '../redux/actions/auth';
import { useDispatch } from 'react-redux';
const AuthContext = createContext()

export const AuthContextProvider = ({children}) => {
    
    const [user, setUser] = useState({})

    const googleSignIn = () =>{

        const provider = new GoogleAuthProvider();
        signInWithPopup(auth, provider)
        .then((res)=>{

            setUser(res)
            
        }).catch((err)=>{
            // toast.error(err.data.message)
            console.log(err, 'err');
        })
    }
    const dispatch = useDispatch()

    useEffect(()=>{
        if (user) {
            const payload = { code: [user?._tokenResponse?.oauthAccessToken], provider: ["google"] };
            console.log("payload", payload);
            dispatch(socialMediaAuth(payload));
        }
    }, [user])

    useEffect(()=>{
        const unsubscribe = onAuthStateChanged(auth, (currentUser)=>{
            setUser(currentUser)
            console.log('user', currentUser);
        })
        return () =>{
            unsubscribe( )
        }
    }, [])
    return (
        <AuthContext.Provider value={{googleSignIn, user}}>
            {children}
        </AuthContext.Provider>
    )
}

export const UserAuth = () =>{
    return useContext(AuthContext)
}