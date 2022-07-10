import React, { useState, useEffect } from "react";
import "./accountSetting.scss";
import { useDispatch, useSelector } from "react-redux";
import { loadUser, updateAccount } from "../../../redux/actions/auth";
import { useFormik } from "formik";
import IMG1 from "../../../assets/user (8) 1.svg";
import IMG3 from "../../../assets/Group.svg";
import IMG2 from "../../../assets/Ellipse 1.svg";
import IMG4 from "../../../assets/plus.svg";
import IMG5 from "../../../assets/g10.svg";
import AddAccountModal from "./modal";
import SuccessOrFailModal from "./modalAlert";
import { Spinner } from "reactstrap";
import UpdatePassword from "./updatePasswordModal";
import rewriteUrl from "../../../helper/rewriteUrl";

const AccountSetting = () => {
  const dispatch = useDispatch();
  const auth = useSelector((state) => state.auth);
  const servicesData = useSelector((state) => state.service);
  const spinner = auth.loading;
  const showAlert = auth?.isUpdateAccount;
  const updateInfo = auth?.updateAccount;
  const successOrFail = servicesData?.updateBankInfo;
  const passwordChanged = auth?.UpdateAccountPassword?.message;

  const showBankInfo = auth?.data?.banking_data?.bank_name;
  const bankInfo = auth?.data?.banking_data;

  useEffect(() => {
    dispatch(loadUser());
  }, []);

  const formik = useFormik({
    initialValues: {
      first_name: auth?.data?.first_name || "",
      last_name: auth?.data?.last_name || "",
      email: auth?.data?.email ||"",
      phone_number: auth?.data?.phone_number ||"",
    },
    onSubmit: (values) => {
      dispatch(updateAccount(values));
    },
    enableReinitialize: true
  });
  return (
    <div className='dashboard-component'>
      {(servicesData?.isupdatebankinfosuccess ||
        auth?.isUpdatePassword) && (
          <SuccessOrFailModal data={successOrFail} datas={passwordChanged} />
        )}
      {showAlert && <SuccessOrFailModal data={updateInfo} />}
      <div className='acct_settings1'>
        <div className='acc__header'>
          <div className='img__wrapper'>
            <img src={IMG1} alt='img' />
          </div>
          <div className='header__title'>
            <h1>Account Settings</h1>
          </div>
        </div>

        {/* <div className='profile__img__wrapper'>
          <div className='profile__img'>
            <img src={IMG2} alt='' />
          </div>
          <div className='img__wrapper'>
            <img src={IMG3} alt='img' />
          </div>
        </div> */}

        <form onSubmit={formik.handleSubmit}>
          <input
            id='first_name'
            name='first_name'
            type='text'
            onChange={formik.handleChange}
            value={formik.values.first_name}
            placeholder='First Name'
            required
          />

          <input
            id='last_name'
            name='last_name'
            type='text'
            onChange={formik.handleChange}
            value={formik.values.last_name}
            placeholder='Last Name'
            required
          />

          <input
            id='email'
            name='email'
            type='email'
            onChange={formik.handleChange}
            value={formik.values.email}
            placeholder='Email Address'
            required
          />

          <input
            id='phone_number'
            name='phone_number'
            type='text'
            onChange={formik.handleChange}
            value={formik.values.phone_number}
            placeholder='Mobile Number'
            required
          />

          {/* <button type='submit'>Update</button> */}
          {spinner ? (
              <button type='submit' disabled>
                <Spinner color='light' />
              </button>
            ) : (
              <button type='submit'>Update</button>
            )}
        </form>
      </div>

      <div className='acct_settings2'>
        <div className='settings__header'>
          <h1>Bank Account Details</h1>
          <AddAccountModal />
        </div>
        <div className='settings__content'>
          <h2>Bank Accounts</h2>
          {showBankInfo == "" ? (
            <>
              <p>Add your bank accounts where your funds will be deposited.</p>
              <div className='settings__message'>
                <p>
                  You have not added a bank account yet,{" "}
                  <span>Add A Bank Account</span>{" "}
                </p>
                <img src={IMG5} alt='' />
              </div>
            </>
          ) : (
            <>
              <div className='bank__account__info mb-5'>
                <h1>{bankInfo?.bank_name}</h1>
                <div className='other__info'>
                  <p className='account__name'>{bankInfo?.account_name}</p>
                  <p className='account__number'>{bankInfo?.account_number}</p>
                </div>
              </div>
            </>
          )}
        </div>

        <div className='settings__header mt-5'>
          <h1>Update Password</h1>
          <UpdatePassword />
        </div>
        {/* <div className='settings__content'>
          <h2>Update Password</h2>
        </div> */}
      </div>
    </div>
  );
};

export default AccountSetting;
