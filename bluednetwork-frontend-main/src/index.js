import React from "react";
import ReactDOM from "react-dom";
import "bootstrap/dist/css/bootstrap.min.css";
import "./components/fontawesomeicon/FontAwesomeTcon";
import "./index.css";
import App from "./App";
import reportWebVitals from "./reportWebVitals";
import { Provider } from "react-redux";
import store from './redux/store'
import { ToastContainer } from "react-toastify";
import { GoogleOAuthProvider } from '@react-oauth/google';

ReactDOM.render(
  <React.StrictMode>
    <GoogleOAuthProvider clientId='1053539387888-1iuthshpm197a24vi5qratb21sen799b.apps.googleusercontent.com'>
      <ToastContainer/>
      <Provider store={store}>
        <App />
      </Provider>
    </GoogleOAuthProvider>
    
  </React.StrictMode>,
  document.getElementById("root")
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
