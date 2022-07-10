import {
  GET_ALL_SERVICE_SUCCESS,
  GET_ALL_SERVICE_FAIL,
  GET_SERVICE_SUCCESS,
  GET_SERVICE_FAIL,
  VARIANT_SUCCESS,
  VARIANT_FAIL,
  QUICK_SERVICE_SUCCESS,
  QUICK_SERVICE_FAIL,
  RESET_TO_FALSE,
  GET_ALL_TRANSACTION_SUCCESS,
  GET_ALL_TRANSACTION_FAIL,
  GET_SINGLE_TRANSACTION_SUCCESS,
  GET_SINGLE_TRANSACTION_FAIL,
  GET_ALL_BANKS_SUCCESS,
  GET_ALL_BANKS_FAIL,
  UPDATE_BANK_DATA_SUCCESS,
  UPDATE_BANK_DATA_FAIL,
  GET_GIFT_CARDS_SUCCESS,
  GET_GIFT_CARDS_FAIL,
  GIFT_CARD_DETAILS_SUCCESS,
  GIFT_CARD_DETAILS_FAIL,
  GIFT_CARD_CURRENCY_RATES_SUCCESS,
  GIFT_CARD_CURRENCY_RATES_FAIL,
  GIFT_CARD_SUCCESS,
  GIFT_CARD_FAIL,
  LOADING,
  GET_AIRTIME_DETAILS_SUCCESS,
  GET_AIRTIME_DETAILS_FAIL,
  GET_PROVIDER_DETAILS_SUCCESS,
  GET_PROVIDER_DETAILS_FAIL,
  GET_BRANDING_DETAILS_SUCCESS,
  GET_BRANDING_DETAILS_FAIL,
  GET_BRANDING_FEATURE_SUCCESS,
  GET_BRANDING_FEATURE_FAIL,
  GET_ADDONS_SUCCESS,
  GET_ADDONS_FAIL,
  GET_PREVIEW_SUCCESS,
  GET_PREVIEW_FAIL,
  GET_BRANDING_PAYMENT_SUCCESS,
  GET_BRANDING_PAYMENT_FAIL,
  GET_PRINTING_PAYMENT_SUCCESS,
  GET_PRINTING_PAYMENT_FAIL,
  GET_AIRTIME_EXCHANGE_SUCCESS,
  GET_AIRTIME_EXCHANGE_FAIL,
  GET_WEB_PAYMENT_SUCCESS,
  GET_WEB_PAYMENT_FAIL,
} from "../actions/service";

const initialState = {
  services: JSON.parse(localStorage.getItem("services")),
  service: [],
  loading: true,
  isSuccess: false,
  isQuickService: false,
  isGiftCardSuccess: false,
  isError: false,
  isAirtimeExchange: false,
  AirtimeExchange: [],
  variant: [],
  transaction: [],
  allTransactions: [],
  singleTransactions: [],
  allBanks: [],
  airtimeDetails: [],
  updateBankInfo: [],
  isupdatebankinfosuccess: false,
  giftCards: [],
  giftCardsDetails: [],
  giftCardSuccess: [],
  providerDetails: [],
  getBrandingCategory: [],
  featureSuccess: [],
  addOns: [],
  previewSuccess: [],
  brandingPaymentSuccess: [],
  isbrandingPaymentSuccess: false,
  webPaymentSuccess: [],
  iswebPaymentSuccess: false,
  printingPaymentSuccess: [],
  isprintingPaymentSuccess: false,
  giftCardsCurrencyRates: [],
};

export default function services (state = initialState, action) {
  const { type, payload } = action;

  switch (type) {
    case RESET_TO_FALSE:
      return {
        ...state,
        loading: false,
        isError: false,
        isSuccess: false,
        isQuickService: false,
        isGiftCardSuccess: false,
        isbrandingPaymentSuccess: false,
        isAirtimeExchange: false,
        isupdatebankinfosuccess: false,
        isprintingPaymentSuccess: false,
        iswebPaymentSuccess: false,
      };
    case LOADING:
      return {
        ...state,
        loading: true,
      };
    case GET_AIRTIME_EXCHANGE_SUCCESS:
      return {
        ...state,
        AirtimeExchange: payload,
        isAirtimeExchange: true,
        loading: false,
      };
    case GET_WEB_PAYMENT_SUCCESS:
      return {
        ...state,
        webPaymentSuccess: payload,
        iswebPaymentSuccess: true,
        loading: false,
      };
    case GET_PRINTING_PAYMENT_SUCCESS:
      return {
        ...state,
        printingPaymentSuccess: payload,
        isprintingPaymentSuccess: true,
        loading: false,
      };
    case GET_BRANDING_PAYMENT_SUCCESS:
      return {
        ...state,
        brandingPaymentSuccess: payload,
        isbrandingPaymentSuccess: true,
        loading: false,
      };
    case GET_PREVIEW_SUCCESS:
      return {
        ...state,
        previewSuccess: payload,
        loading: false,
      };
    case GET_ADDONS_SUCCESS:
      return {
        ...state,
        addOns: payload,
        loading: false,
      };
    case GET_BRANDING_FEATURE_SUCCESS:
      return {
        ...state,
        featureSuccess: payload,
        loading: false,
      };
    case GET_BRANDING_DETAILS_SUCCESS:
      return {
        ...state,
        getBrandingCategory: payload,
        loading: false,
      };
    case GET_PROVIDER_DETAILS_SUCCESS:
      return {
        ...state,
        providerDetails: payload,
        loading: false,
      };
    case GIFT_CARD_SUCCESS:
      return {
        ...state,
        giftCardSuccess: payload,
        loading: false,
        isGiftCardSuccess: true,
      };
    case GET_AIRTIME_DETAILS_SUCCESS:
      return {
        ...state,
        airtimeDetails: payload,
        loading: false,
        isSuccess: true,
      };
    case GIFT_CARD_CURRENCY_RATES_SUCCESS:
      return {
        ...state,
        giftCardsCurrencyRates: payload,
        loading: false,
        isSuccess: true,
      };
    case GIFT_CARD_DETAILS_SUCCESS:
      return {
        ...state,
        giftCardsDetails: payload,
        loading: false,
        isSuccess: true,
      };
    case GET_GIFT_CARDS_SUCCESS:
      return {
        ...state,
        giftCards: payload,
        loading: false,
        isSuccess: true,
      };
    case UPDATE_BANK_DATA_SUCCESS:
      return {
        ...state,
        updateBankInfo: payload,
        isupdatebankinfosuccess: true,
        loading: false,
      };
    case GET_ALL_BANKS_SUCCESS:
      return {
        ...state,
        allBanks: payload,
        loading: false,
        isSuccess: true,
      };
    case GET_SINGLE_TRANSACTION_SUCCESS:
      return {
        ...state,
        singleTransactions: payload,
        loading: false,
        isSuccess: true,
      };
    case GET_ALL_TRANSACTION_SUCCESS:
      return {
        ...state,
        allTransactions: payload,
        loading: false,
        isSuccess: true,
      };
    case QUICK_SERVICE_SUCCESS:
      return {
        ...state,
        transaction: payload,
        loading: false,
        isQuickService: true,
        isError: true,
      };
    case VARIANT_SUCCESS:
      return {
        ...state,
        variant: payload,
        loading: false,
      };
    case GET_ALL_SERVICE_SUCCESS:
      localStorage.setItem("services", JSON.stringify(payload));
      return {
        ...state,
        ...payload,
        loading: false,
      };
    case GET_SERVICE_SUCCESS:
      return {
        ...state,
        service: payload,
      };
    case GET_WEB_PAYMENT_FAIL:
    case GET_PRINTING_PAYMENT_FAIL:
    case GET_AIRTIME_EXCHANGE_FAIL:
    case GET_BRANDING_PAYMENT_FAIL:
    case GET_BRANDING_FEATURE_FAIL:
    case GET_BRANDING_DETAILS_FAIL:
    case GET_AIRTIME_DETAILS_FAIL:
    case GET_SINGLE_TRANSACTION_FAIL:
    case GET_ALL_TRANSACTION_FAIL:
    case GET_ALL_SERVICE_FAIL:
    case GET_SERVICE_FAIL:
    case VARIANT_FAIL:
    case QUICK_SERVICE_FAIL:
    case GET_ALL_BANKS_FAIL:
    case UPDATE_BANK_DATA_FAIL:
    case GET_GIFT_CARDS_FAIL:
    case GIFT_CARD_DETAILS_FAIL:
    case GIFT_CARD_CURRENCY_RATES_FAIL:
    case GIFT_CARD_FAIL:
    case GET_PROVIDER_DETAILS_FAIL:
    case GET_ADDONS_FAIL:
    case GET_PREVIEW_FAIL:
      return {
        ...state,
        loading: false,
        isError: true,
        isSuccess: false,
        isAirtimeFailed: payload,
        payload,
      };
    default:
      return state;
  }
}
