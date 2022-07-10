import {
  REGISTER_SUCCESS,
  REGISTER_FAIL,
  USER_LOADED,
  AUTH_ERROR,
  LOGIN_SUCCESS,
  LOGIN_FAIL,
  LOGOUT,
  FORGOT_PASSWORD_SUCCESS,
  FORGOT_PASSWORD_FAIL,
  SOCIAL_LOGIN_SUCCESS,
  SOCIAL_LOGIN_FAIL,
  UPDATE_PASSWORD_SUCCESS,
  UPDATE_PASSWORD_FAIL,
  UPDATE_ACCOUNT_SUCCESS,
  UPDATE_ACCOUNT_FAIL,
  RESET_TO_FALSE,
  LOGIN_LOADING,
  LOADING,
  UPDATE_ACCOUNT_PASSWORD_SUCCESS,
  UPDATE_ACCOUNT_PASSWORD_FAIL,
} from "../actions/auth";

const initialState = {
  access_token: localStorage.getItem("access_token"),
  isAuthenticated: JSON.parse(localStorage.getItem("data")),
  loading: false,
  data: null,
  updateAccount: [],
  isUpdateAccount: false,
  isUpdatePassword: false,
  isError: false,
  UpdateAccountPassword: [],
};

export default function (state = initialState, action) {
  const { type, payload } = action;

  switch (type) {
    case LOGIN_LOADING:
      return {
        ...state,
        loading: true,
      };
    case LOADING:
      return {
        ...state,
        loading: true,
      };
    case RESET_TO_FALSE:
      return {
        ...state,
        // updateAccount: payload,
        // isAuthenticated: true,
        loading: false,
        isUpdateAccount: false,
        isUpdatePassword: false,
        isError: false,
      };
    case UPDATE_ACCOUNT_PASSWORD_SUCCESS:
      return {
        ...state,
        loading: false,
        isUpdatePassword: true,
        UpdateAccountPassword: payload,
      };
    case UPDATE_ACCOUNT_PASSWORD_FAIL:
      return {
        ...state,
        loading: false,
        isError: true,
        UpdateAccountPassword: payload,
      };
    case UPDATE_ACCOUNT_SUCCESS:
      return {
        ...state,
        updateAccount: payload,
        isAuthenticated: true,
        loading: false,
        isUpdateAccount: true,
      };
    case REGISTER_SUCCESS:
    case LOGIN_SUCCESS:
    case SOCIAL_LOGIN_SUCCESS:
      localStorage.setItem(
        "access_token",
        payload.data?.authorization?.access_token
      );
      localStorage.setItem("data", JSON.stringify(payload.data));
      return {
        ...state,
        ...payload,
        isAuthenticated: true,
        loading: false,
      };
    case USER_LOADED:
      localStorage.setItem("user", JSON.stringify(payload.data));
      return {
        ...state,
        // ...payload,
        data: payload.data,
        loading: false,
      };
    case FORGOT_PASSWORD_SUCCESS:
      return { ...state, payload };
    case FORGOT_PASSWORD_FAIL:
      return { ...state };
    case UPDATE_PASSWORD_SUCCESS:
      return { ...state, payload };
    case UPDATE_PASSWORD_FAIL:
      return { ...state };
    case UPDATE_ACCOUNT_FAIL:
      return { ...state };
    case REGISTER_FAIL:
    case LOGOUT:
    case LOGIN_FAIL:
    // case AUTH_ERROR:
    case SOCIAL_LOGIN_FAIL:
      localStorage.removeItem("access_token");
      localStorage.removeItem("data");
      return {
        ...state,
        access_token: null,
        isAuthenticated: false,
        loading: false,
      };
    default:
      return state;
  }
}
