import React from "react";
// import "./index.scss";
import { withRouter, Route, Switch, Redirect } from "react-router-dom";
import DashboardLayout from "../../layouts/dashboardlayout/index.jsx";
import Bill from "./bill"
import Home from "./dashboardHome/DashboardHome"
import Branding from "./dashboardBranding/"
import Printing from "./dashboardPrinting/"
import Web from "./dashboardWeb/"
import Exchange from "./dashboardExchange/DashboardExchange"
import Transactions from "./dashboardTransactions"
import AccountSetting from "./dashboardAccountSetting/AccountSetting";
import Wallet from "./dashboardWallet";
import DynamicView from "./dynamicView";
import DashboardOffers from "./dashboardOffers/index.jsx";


function Dashboard(props) {
  const {
    match: { path },
  } = props;

  console.log(props.match.path)
  return (
    <DashboardLayout>
      <Switch>
        <Route exact path={path}>
          <Redirect to={`${path}/dashboard`} />
        </Route>
        <Route path={`${path}/dashboard`}>
          <Home />
        </Route>
        <Route path={`${path}/bd_bills`}>
          <Bill />
        </Route>
        {/* <Route path={`${path}/bd_branding`}>
          <Branding />
        </Route>
        <Route path={`${path}/bd_printing`}>
          <Printing/>
        </Route>
        <Route path={`${path}/bd_web`}>
          <Web  />
        </Route> */}
        {/* <Route path={`${path}/bd_exchange`}>
          <Exchange/>
        </Route> */}
        <Route path={`${path}/bd_swap`}>
          <Exchange/>
        </Route>
        <Route path={`${path}/bd_transactions`}>
          <Transactions/>
        </Route>
        <Route path={`${path}/bd_offers`}>
          <DashboardOffers />
        </Route>
        <Route path={`${path}/account_setting`}>
          <AccountSetting />
        </Route> 
        <Route path={`${path}/wallet`}>
          <Wallet />
        </Route> 
        
        <Route path={`${path}/:id`}>
          <DynamicView />
        </Route>
      </Switch>
    </DashboardLayout>
  );
}

export default withRouter(Dashboard);
