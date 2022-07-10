import axios from "axios";
import { toast } from "react-toastify";
import handleCreateVirtualAccountFunct from "../../components/form/hooks/handleCreateVirtualAcct";
import useVirtualAccount from "../../components/form/hooks/useVirtualAccount";
import rewriteUrl from "../../helper/rewriteUrl";

export const REGISTER_SUCCESS = "REGISTER_SUCCESS";
export const REGISTER_FAIL = "REGISTER_FAIL";

export const UPDATE_ACCOUNT_SUCCESS = "UPDATE_ACCOUNT_SUCCESS";
export const UPDATE_ACCOUNT_FAIL = "UPDATE_ACCOUNT_FAIL";

export const USER_LOADED = "USER_LOADED";
export const AUTH_ERROR = "AUTH_ERROR";

export const LOGIN_SUCCESS = "LOGIN_SUCCESS";
export const LOGIN_FAIL = "LOGIN_FAIL";

export const SOCIAL_LOGIN_SUCCESS = "SOCIAL_LOGIN_SUCCESS";
export const SOCIAL_LOGIN_FAIL = "SOCIAL_LOGIN_FAIL";

export const FORGOT_PASSWORD_SUCCESS = "FORGOT_PASSWORD_SUCCESS";
export const FORGOT_PASSWORD_FAIL = "FORGOT_PASSWORD_FAIL";

export const UPDATE_PASSWORD_SUCCESS = "UPDATE_PASSWORD_SUCCESS";
export const UPDATE_PASSWORD_FAIL = "UPDATE_PASSWORD_FAIL";

export const LOGOUT = "LOGOUT";

export const LOGIN_LOADING = "LOGIN_LOADING";

export const LOADING = "LOADING";

export const RESET_TO_FALSE = "RESET_TO_FALSE";

export const UPDATE_ACCOUNT_PASSWORD_SUCCESS =
  "UPDATE_ACCOUNT_PASSWORD_SUCCESS";
export const UPDATE_ACCOUNT_PASSWORD_FAIL = "UPDATE_ACCOUNT_PASSWORD_FAIL";

export const MESSAGE_SUCCESS = "MESSAGE_SUCCESS";
export const MESSAGE_FAIL = "MESSAGE_FAIL";

export const resetAllToFalse = () => (dispatch) => {
  dispatch({
    type: RESET_TO_FALSE,
  });
};

// LOAD user
export const loadUser = () => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/account/me`,
      config
    );
    dispatch({
      type: USER_LOADED,
      payload: res.data,
    });
  } catch (error) {
    dispatch({
      type: AUTH_ERROR,
    });
    toast.error(error?.response?.data?.message || "An error occured")
  }
};

// Register User
export const register = (values) => async (dispatch) => {

  dispatch({
    type: LOGIN_LOADING,
  });
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  const body = JSON.stringify(values);

  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/register`,
      body,
      config
    )
    handleCreateVirtualAccountFunct(values?.email)
    loadUser();
    dispatch({
      type: REGISTER_SUCCESS,
      payload: res?.data,
    });
    console.log("register", res?.data);
  } catch (error) {
    console.log("error", error);
    dispatch({
      type: REGISTER_FAIL,
    });
    toast.error(error?.response?.data?.message);
    return error;
  }
};

// Login User
export const login = (values) => async (dispatch) => {
  dispatch({
    type: LOGIN_LOADING,
  });
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  const body = JSON.stringify(values);

  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/auth/login`,
      body,
      config
    );
    loadUser();
    dispatch({
      type: LOGIN_SUCCESS,
      payload: res?.data,
    });
    console.log("login", res?.data);
    // toast.success("MY SUCCESS");
  } catch (error) {
    console.log("error", error.response?.data?.message);
    dispatch({
      type: LOGIN_FAIL,
    });
    toast.error(error.response?.data?.message);
    return error;
  }
};

// socialMediaAuth
export const socialMediaAuth = (payload) => async (dispatch) => {
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/auth/social-login/callback?code=${payload.code}&provider=${payload.provider}`,
      config
    );
    loadUser();
    dispatch({
      type: SOCIAL_LOGIN_SUCCESS,
      payload: res?.data,
    });
    console.log("social payload", res?.data);
  } catch (error) {
    console.log("error", error.message);
    dispatch({
      type: SOCIAL_LOGIN_FAIL,
    });
    return error;
  }
};

export const socialMediaAuthReact = (payload) => async (dispatch) => {
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/auth/social-login/callback/react?code=${payload.code}&provider=${payload.provider}`,
      config
    );
    loadUser();
    dispatch({
      type: SOCIAL_LOGIN_SUCCESS,
      payload: res?.data,
    });
    console.log("social payload", res?.data);
  } catch (error) {
    console.log("error", error.message);
    dispatch({
      type: SOCIAL_LOGIN_FAIL,
    });
    return error;
  }
};

// Forgot password
export const forgotPassword = (values) => async (dispatch) => {
  dispatch({
    type: LOGIN_LOADING,
  });
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  const body = JSON.stringify(values);

  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/password/reset_request`,
      body,
      config
    );
    dispatch({
      type: FORGOT_PASSWORD_SUCCESS,
      payload: res?.data,
    });
    console.log("forgot password", res?.data);
  } catch (error) {
    dispatch({
      type: FORGOT_PASSWORD_FAIL,
    });
    return error;
  }
};

// UPDATE PASSWORD
export const updatePassword = (payload) => async (dispatch) => {
  dispatch({
    type: LOGIN_LOADING,
  });
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  const body = JSON.stringify(payload);
  console.log(body);
  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/password/reset_save`,
      body,
      config
    );
    dispatch({
      type: UPDATE_PASSWORD_SUCCESS,
      payload: res?.data,
    });
    console.log("forgot password", res?.data);
  } catch (error) {
    dispatch({
      type: UPDATE_PASSWORD_FAIL,
    });
    console.log("error", error.message);
    return error;
  }
};

// UPDATE ACCOUNT PASSWORD
export const updateAccountPassword = (values) => async (dispatch) => {
  const token = localStorage.getItem("access_token");
  dispatch({
    type: LOADING,
  });

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  // const body = JSON.stringify(values);
  // console.log("hey", body);
  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/account/update_password`,
      values,
      config
    );
    dispatch({
      type: UPDATE_ACCOUNT_PASSWORD_SUCCESS,
      payload: res?.data,
    });
    console.log("UPDATE_ACCOUNT_PASSWORD_SUCCESS", res?.data);
  } catch (error) {
    let payload = error.response.data.message;
    dispatch({
      type: UPDATE_ACCOUNT_PASSWORD_FAIL,
      payload,
    });
    console.log("UPDATE_ACCOUNT_PASSWORD_FAIL", error.response.data.message);
    return error;
  }
};

// Update Account
export const updateAccount = (values) => async (dispatch) => {
  dispatch({
    type: LOADING,
  });
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  const body = JSON.stringify(values);
  console.log("body", values);

  // return

  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/account/update_basic`,
      values,
      config
    );
    loadUser();
    dispatch({
      type: UPDATE_ACCOUNT_SUCCESS,
      payload: res?.data,
    });
    console.log("UPDATE_ACCOUNT_SUCCESS", res?.data);
  } catch (error) {
    console.log("UPDATE_ACCOUNT_FAIL", error.message);
    dispatch({
      type: UPDATE_ACCOUNT_FAIL,
    });
    return error;
  }
};

// LOGOUT
export const logout = () => (dispatch) => {
  dispatch({
    type: LOGOUT,
  });
};
