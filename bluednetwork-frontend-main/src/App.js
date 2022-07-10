import React from "react";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import "./App.css";
import Login from "./pages/auth/Login";
import SignUp from "./pages/auth/SignUp";
import Verify from "./pages/auth/Verify";
import Home from "./pages/home/Home";
import Web from "./pages/web/index";
import Bill from "./pages/bill/index";
import Exchange from "./pages/exchange";
import Recover from "./pages/auth/Recover";
import UpdatePassword from "./pages/auth/UpdatePassword";
import Career from "./pages/Career";
import PrivatePolicy from "./pages/private-policy/PrivatePolicy";
import About from "./pages/about/index";
import TermsAndCondition from "./pages/terms-and-condition/TermsAndCondition";
import Graphics from "./pages/graphics/index";
import Printing from "./pages/printing/index";
import Blogs from "./pages/blog/Blogs";
import Blog from "./pages/blog/Blog";
import Contact from "./pages/ContactUs/Contact";
import FAQ from "./pages/faq";
import VerifyEmail from "./pages/auth/VerifyEmail";

import PrivateRoute from "./pages/routing/PrivateRoute";
import AppDashboard from "./pages/appDashboard";
import { AuthContextProvider } from "./context/AuthContext";

function App() {
  return (
    <div className='App'>
      <AuthContextProvider>
        <Router>
          <Switch>
            {/* <Route exact path='/modal' component={GiftCardExchangeModal} /> */}
            <Route exact path='/' component={Home} />
            <Route exact path='/web' component={Web} />
            <Route exact path='/bd-bills' component={Bill} />
            <Route exact path='/blogs' component={Blogs} />
            <Route exact path='/blog/1' component={Blog} />
            <Route exact path='/exchange' component={Exchange} />
            <Route exact path='/login' component={Login} />
            <Route exact path='/sign-up' component={SignUp} />
            <Route exact path='/about-us' component={About} />
            <Route exact path='/graphics-design-branding' component={Graphics} />
            <Route exact path='/printing' component={Printing} />
            <Route exact path='/verify' component={VerifyEmail} />
            <Route exact path='/verify-email' component={VerifyEmail} />
            <Route exact path='/recover-password' component={Recover} />
            <Route exact path='/update-password' component={UpdatePassword} />
            <Route exact path='/career' component={Career} />
            <Route exact path='/private-policy' component={PrivatePolicy} />
            <Route
              exact
              path='/terms-and-condition'
              component={TermsAndCondition}
            />
            <Route exact path='/contact' component={Contact} />
            <Route exact path='/faq' component={FAQ} />
            <PrivateRoute path='/app' component={AppDashboard} />
          </Switch>
        </Router>
      </AuthContextProvider>
      
    </div>
  );
}

export default App;
