// Import the functions you need from the SDKs you need

import { initializeApp } from "firebase/app";

import { getAnalytics } from "firebase/analytics";

import { getAuth } from "firebase/auth";

// TODO: Add SDKs for Firebase products that you want to use

// https://firebase.google.com/docs/web/setup#available-libraries


// Your web app's Firebase configuration

// For Firebase JS SDK v7.20.0 and later, measurementId is optional

const firebaseConfig = {

  apiKey: "AIzaSyBSiyHFFVykmXGLKl0024acC3HnUJPOit0",

  authDomain: "blue-d-37456.firebaseapp.com",

  projectId: "blue-d-37456",

  storageBucket: "blue-d-37456.appspot.com",

  messagingSenderId: "888120669873",

  appId: "1:888120669873:web:00305a46610ed6f255d490",

  measurementId: "G-L5GGFRVK3M"

};


// Initialize Firebase

export const app = initializeApp(firebaseConfig);

const analytics = getAnalytics(app);

// Initialize Firebase Authentication and get a reference to the service
export const auth = getAuth(app);
