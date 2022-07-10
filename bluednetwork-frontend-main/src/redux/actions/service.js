import axios from "axios";
import { toast } from "react-toastify";
import rewriteUrl from "../../helper/rewriteUrl";
import { loadUser } from "./auth";

export const GET_ALL_SERVICE_SUCCESS = "GET_ALL_SERVICE_SUCCESS";
export const GET_ALL_SERVICE_FAIL = "GET_ALL_SERVICE_FAIL";

export const GET_SERVICE_SUCCESS = "GET_SERVICE_SUCCESS";
export const GET_SERVICE_FAIL = "GET_SERVICE_FAIL";

export const GET_GIFT_CARDS_SUCCESS = "GET_GIFT_CARDS_SUCCESS";
export const GET_GIFT_CARDS_FAIL = "GET_GIFT_CARDS_FAIL";

export const QUICK_SERVICE_SUCCESS = "QUICK_SERVICE_SUCCESS";
export const QUICK_SERVICE_FAIL = "QUICK_SERVICE_FAIL";

export const GET_WALLET_SUCCESS = "GET_WALLET_SUCCESS";
export const GET_WALLET_FAIL = "GET_WALLET_FAIL";

export const UPDATE_BANK_DATA_SUCCESS = "UPDATE_BANK_DATA_SUCCESS";
export const UPDATE_BANK_DATA_FAIL = "UPDATE_BANK_DATA_FAIL";

export const GET_ALL_BANKS_SUCCESS = "GET_ALL_BANKS_SUCCESS";
export const GET_ALL_BANKS_FAIL = "GET_ALL_BANKS_FAIL";

export const GET_ALL_TRANSACTION_SUCCESS = "GET_ALL_TRANSACTION_SUCCESS";
export const GET_ALL_TRANSACTION_FAIL = "GET_ALL_TRANSACTION_FAIL";

export const GET_SINGLE_TRANSACTION_SUCCESS = "GET_SINGLE_TRANSACTION_SUCCESS";
export const GET_SINGLE_TRANSACTION_FAIL = "GET_SINGLE_TRANSACTION_FAIL";

export const VARIANT_SUCCESS = "VARIANT_SUCCESS";
export const VARIANT_FAIL = "VARIANT_FAIL";

export const GIFT_CARD_DETAILS_SUCCESS = "GIFT_CARD_DETAILS_SUCCESS";
export const GIFT_CARD_DETAILS_FAIL = "GIFT_CARD_DETAILS_FAIL";

export const GIFT_CARD_CURRENCY_RATES_SUCCESS =
  "GIFT_CARD_CURRENCY_RATES_SUCCESS";
export const GIFT_CARD_CURRENCY_RATES_FAIL = "GIFT_CARD_CURRENCY_RATES_FAIL";

export const GIFT_CARD_SUCCESS = "GIFT_CARD_SUCCESS";
export const GIFT_CARD_FAIL = "GIFT_CARD_FAIL";

export const GET_AIRTIME_DETAILS_SUCCESS = "GET_AIRTIME_DETAILS_SUCCESS";
export const GET_AIRTIME_DETAILS_FAIL = "GET_AIRTIME_DETAILS_FAIL";

export const GET_AIRTIME_EXCHANGE_SUCCESS = "GET_AIRTIME_EXCHANGE_SUCCESS";
export const GET_AIRTIME_EXCHANGE_FAIL = "GET_AIRTIME_EXCHANGE_FAIL";

export const GET_PROVIDER_DETAILS_SUCCESS = "GET_PROVIDER_DETAILS_SUCCESS";
export const GET_PROVIDER_DETAILS_FAIL = "GET_PROVIDER_DETAILS_FAIL";

export const GET_BRANDING_DETAILS_SUCCESS = "GET_BRANDING_DETAILS_SUCCESS";
export const GET_BRANDING_DETAILS_FAIL = "GET_BRANDING_DETAILS_FAIL";

export const GET_BRANDING_FEATURE_SUCCESS = "GET_BRANDING_FEATURE_SUCCESS";
export const GET_BRANDING_FEATURE_FAIL = "GET_BRANDING_FEATURE_FAIL";

export const GET_ADDONS_SUCCESS = "GET_ADDONS_SUCCESS";
export const GET_ADDONS_FAIL = "GET_ADDONS_FAIL";

export const GET_PREVIEW_SUCCESS = "GET_PREVIEW_SUCCESS";
export const GET_PREVIEW_FAIL = "GET_PREVIEW_FAIL";

export const GET_BRANDING_PAYMENT_SUCCESS = "GET_BRANDING_PAYMENT_SUCCESS";
export const GET_BRANDING_PAYMENT_FAIL = "GET_BRANDING_PAYMENT_FAIL";

export const GET_WEB_PAYMENT_SUCCESS = "GET_WEB_PAYMENT_SUCCESS";
export const GET_WEB_PAYMENT_FAIL = "GET_WEB_PAYMENT_FAIL";

export const GET_PRINTING_PAYMENT_SUCCESS = "GET_PRINTING_PAYMENT_SUCCESS";
export const GET_PRINTING_PAYMENT_FAIL = "GET_PRINTING_PAYMENT_FAIL";

export const RESET_TO_FALSE = "RESET_TO_FALSE";

export const LOADING = "LOADING";

export const resetAllToFalse = () => (dispatch) => {
  dispatch({
    type: RESET_TO_FALSE,
  });
};

// ${rewriteUrl()}front_api/service/order/graphic-design/web

// getBrandingPayment
export const getWebPayment =
  ({
    website_title,
    domain_name,
    description,
    brand_color,
    additional_information,
    post_title,
    content_goals,
    is_preview,
    serviceType,
    service_key: categoryKey,
    "design_document[]": formData,
    add_ons,
    type,
  }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      const mode_of_payment = "wallet";

      const paylod = {
        custom_fields: {
          website_title,
          domain_name,
          description,
          brand_color,
          post_title,
          content_goals,
        },
        additional_information,
        is_preview,
        serviceType: "bd_web",
        service_key: serviceType,
        variant_key: categoryKey,
        "design_document[]": formData,
        addons: add_ons,
        ...type,
        mode_of_payment,
      };

      console.log("paylod333", paylod);

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/${serviceType}/web`,
          paylod,
          config
        );
        dispatch({
          type: GET_WEB_PAYMENT_SUCCESS,
          payload: res?.data,
        });
        console.log("GET_WEB_PAYMENT_SUCCESS", res?.data);
      } catch (error) {
        console.log("GET_WEB_PAYMENT_FAIL", error.message);
        dispatch({
          type: GET_WEB_PAYMENT_FAIL,
        });
        return error;
      }
    };

// getBrandingPayment
export const getBrandingPayment =
  ({
    business_name: name,
    brand_color: colors,
    slogan,
    description: description,
    is_preview,
    serviceType,
    package_key: categoryKey,
    proof_document,
    add_ons,
    type,
  }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      const mode_of_payment = "wallet";

      // add service key
      // its either graphic-design or cac-registration

      const paylod = {
        custom_fields: {
          business_name: name,
          brand_color: colors,
          description: description,
          slogan: slogan,
        },
        is_preview,
        service_type_slug: serviceType,
        // package_key: categoryKey,
        variant_key: categoryKey,
        proof_document,
        addons: add_ons,
        ...type,
        mode_of_payment,
      };

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/branding/graphics`,
          paylod,
          config
        );
        dispatch({
          type: GET_BRANDING_PAYMENT_SUCCESS,
          payload: res?.data,
        });
        console.log("GET_BRANDING_PAYMENT_SUCCESS", res?.data);
      } catch (error) {
        console.log("GET_BRANDING_PAYMENT_FAIL", error.message);
        dispatch({
          type: GET_BRANDING_PAYMENT_FAIL,
        });
        return error;
      }
    };

// getPrintingPayment
export const getPrintingPayment =
  ({
    quantity,
    number_of_pages,
    description,
    additional_information,
    is_preview,
    serviceType,
    "design_document[]": formData,
    service_key: categoryKey,
    add_ons,
    type,
  }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      const mode_of_payment = "wallet";

      const paylod = {
        custom_fields: {
          quantity,
          number_of_pages,
          description,
          additional_information,
        },
        is_preview,
        serviceType,
        service_key: categoryKey,
        "design_document[]": formData,
        addons: add_ons,
        ...type,
        mode_of_payment,
      };

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/printing`,
          paylod,
          config
        );
        dispatch({
          type: GET_PRINTING_PAYMENT_SUCCESS,
          payload: res?.data,
        });
        console.log("GET_PRINTING_PAYMENT_SUCCESS", res?.data);
      } catch (error) {
        console.log("GET_PRINTING_PAYMENT_FAIL", error.message);
        dispatch({
          type: GET_PRINTING_PAYMENT_FAIL,
        });
        return error;
      }
    };
export const getWebPreviewDynamic =
  (paylod, cb, finallyCb) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };
      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/preview`,
          paylod,
          config
        );
        dispatch({
          type: GET_PREVIEW_SUCCESS,
          payload: res?.data,
        });
        if (cb) {
          cb()
        }
      } catch (error) {
        toast.error(error?.response?.data?.message || error?.message || "An error occured")
        dispatch({
          type: GET_PREVIEW_FAIL,
        });
        // return error;
      } finally {
        if (finallyCb) {
          finallyCb()
        }
      }
    };


export const getWebPreview =
  ({
    website_title,
    domain_name,
    description,
    brand_color,
    additional_information,
    post_title,
    content_goals,
    is_preview,
    serviceType,
    service_key: categoryKey,
    "design_document[]": formData,
    add_ons,
    type,
  }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      const paylod = {
        custom_fields: {
          website_title,
          domain_name,
          description,
          brand_color,
          post_title,
          content_goals,
        },
        additional_information,
        is_preview,
        serviceType: "bd_web",
        service_key: serviceType,
        variant_key: categoryKey,
        "design_document[]": formData,
        addons: add_ons,
        ...type,
      };

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/preview`,
          paylod,
          config
        );
        dispatch({
          type: GET_PREVIEW_SUCCESS,
          payload: res?.data,
        });
        console.log("GET_PREVIEW_SUCCESS", res?.data);
      } catch (error) {
        console.log("GET_PREVIEW_FAIL", error.message);
        dispatch({
          type: GET_PREVIEW_FAIL,
        });
        return error;
      }
    };

// GET_BRANDING_FEATURE_SUCCESS
export const getPreviewBranding =
  ({
    nature_of_ngo,
    ngo_name,
    ngo_email,
    ngo_phone_number,
    ngo_address,
    brand_color,
    business_name,
    business_email,
    nature_of_business,
    business_phone_number,
    phone_number,
    full_name,
    business_address,
    address,
    email,
    additional_information_1,
    slogan,
    description,
    additional_information,
    is_preview,
    serviceType,
    service_key: selectedCategory,
    type,
    add_ons,
    formData,
  }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      const paylod = {
        custom_fields: {
          nature_of_ngo,
          ngo_name,
          ngo_email,
          ngo_phone_number,
          ngo_address,
          brand_color,
          business_name,
          business_email,
          nature_of_business,
          business_phone_number,
          phone_number,
          full_name,
          business_address,
          address,
          email,
          slogan,
          description,
        },
        additional_information,
        additional_information_1,
        is_preview,
        service_type_slug: "bd-branding",
        // serviceType: "bd_branding",
        // service_key: selectedCategory
        variant_key: selectedCategory,
        service_key: serviceType,
        formData,
        addons: add_ons,
        ...type,
      };

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/preview`,
          formData,
          config
        );
        dispatch({
          type: GET_PREVIEW_SUCCESS,
          payload: res?.data,
        });
        console.log("GET_PREVIEW_SUCCESS", res?.data);
      } catch (error) {
        console.log("GET_PREVIEW_FAIL", error.message);
        dispatch({
          type: GET_PREVIEW_FAIL,
        });
        return error;
      }
    };

// GET_BRANDING_FEATURE_SUCCESS
export const getPreview =
  ({
    quantity,
    description,
    number_of_pages,
    additional_information,
    business_name: name,
    brand_color: colors,
    slogan,
    // description: description,
    is_preview,
    serviceType,
    service_key: categoryKey,
    "design_document[]": formData,
    add_ons,
    type,
  }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      const paylod = {
        custom_fields: {
          business_name: name,
          brand_color: colors,
          description: description,
          slogan: slogan,
          number_of_pages,
          quantity,
        },
        additional_information,
        is_preview,
        serviceType,
        service_key: categoryKey,
        "design_document[]": formData,
        addons: add_ons,
        ...type,
      };

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/preview`,
          paylod,
          config
        );
        dispatch({
          type: GET_PREVIEW_SUCCESS,
          payload: res?.data,
        });
        console.log("GET_PREVIEW_SUCCESS", res?.data);
      } catch (error) {
        console.log("GET_PREVIEW_FAIL", error.message);
        dispatch({
          type: GET_PREVIEW_FAIL,
        });
        return error;
      }
    };

// GET_BRANDING_FEATURE_SUCCESS
export const brandingFeature = (id) => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };
  // service_variant
  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/service/features/${id}/service`,
      { featureable_id: id, type: "service" },
      config
    );
    dispatch({
      type: GET_BRANDING_FEATURE_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_BRANDING_FEATURE_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_BRANDING_FEATURE_FAIL", error.message);
    dispatch({
      type: GET_BRANDING_FEATURE_FAIL,
    });
    return error;
  }
};

// Branding api
export const addOns = (key) => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  console.log("keyddddddddd", key);

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/service/single/${key}`,
      config
    );
    dispatch({
      type: GET_ADDONS_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_ADDONS_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_ADDONS_FAIL", error.message);
    dispatch({
      type: GET_ADDONS_FAIL,
    });
    return error;
  }
};

// Branding api
export const brandingCategory = (key) => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/service/single/${key}`,
      config
    );
    dispatch({
      type: GET_BRANDING_DETAILS_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_BRANDING_DETAILS_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_BRANDING_DETAILS_FAIL", error.message);
    dispatch({
      type: GET_BRANDING_DETAILS_FAIL,
    });
    return error;
  }
};

// airtimeExchange
export const airtimeExchange = (formData) => async (dispatch) => {
  const token = localStorage.getItem("access_token");
  dispatch({
    type: LOADING,
  });
  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/service/order/exchange/airtimetocash`,
      formData,
      config
    );
    dispatch({
      type: GET_AIRTIME_EXCHANGE_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_AIRTIME_EXCHANGE_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_AIRTIME_EXCHANGE_FAIL", error.response.data.message);
    dispatch({
      type: GET_AIRTIME_EXCHANGE_FAIL,
      payload: error.response.data.message,
    });
    toast.error(error.response.data.message)
    return error;
  }
};

// Get Airtime details
export const getAirtimeData = (values) => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/service/single/airtime-for-cash`,
      values,
      config
    );
    dispatch({
      type: GET_AIRTIME_DETAILS_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_AIRTIME_DETAILS_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_AIRTIME_DETAILS_FAIL", error.message);
    dispatch({
      type: GET_AIRTIME_DETAILS_FAIL,
    });
    return error;
  }
};

// Get Airtime details
export const getProviderDetails = (key) => async (dispatch) => {
  const token = localStorage.getItem("access_token");
  dispatch({
    type: LOADING,
  });
  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };
  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/service/single/${key}`,
      config
    );
    dispatch({
      type: GET_PROVIDER_DETAILS_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_PROVIDER_DETAILS_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_PROVIDER_DETAILS_FAIL", error.message);
    dispatch({
      type: GET_PROVIDER_DETAILS_FAIL,
    });
    return error;
  }
};

// getGiftCardSuccess
export const getGiftCardSuccess = (payload) => async (dispatch) => {
  const token = localStorage.getItem("access_token");
  dispatch({
    type: LOADING,
  });
  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/service/order/exchange/giftcard`,
      payload,
      config
    );
    dispatch({
      type: GIFT_CARD_SUCCESS,
      payload: res?.data,
    });
    console.log("GIFT_CARD_SUCCESS", res?.data);
  } catch (error) {
    console.log("GIFT_CARD_FAIL", error.message);
    dispatch({
      type: GIFT_CARD_FAIL,
    });
    return error;
  }
};

// getGiftCardDetails
export const getGiftCardDetails = (id) => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/gift_card/single/${id}`,
      config
    );
    dispatch({
      type: GIFT_CARD_DETAILS_SUCCESS,
      payload: res?.data,
    });
    console.log("GIFT_CARD_DETAILS_SUCCESS", res?.data);
  } catch (error) {
    console.log("GIFT_CARD_DETAILS_FAIL", error.message);
    dispatch({
      type: GIFT_CARD_DETAILS_FAIL,
    });
    return error;
  }
};

// getGiftCardDetails
export const getGiftCardCurrencyRates =
  ({ gift_card_id, gift_card_currency_id, gift_card_category_id }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      try {
        const res = await axios.get(
          `${rewriteUrl()}front_api/gift_card/single/${gift_card_id}/${gift_card_currency_id}/${gift_card_category_id}/currency_rates`,
          config
        );
        dispatch({
          type: GIFT_CARD_CURRENCY_RATES_SUCCESS,
          payload: res?.data,
        });
        console.log("GIFT_CARD_CURRENCY_RATES_SUCCESS", res?.data);
      } catch (error) {
        console.log("GIFT_CARD_CURRENCY_RATES_FAIL", error.message);
        dispatch({
          type: GIFT_CARD_CURRENCY_RATES_FAIL,
        });
        return error;
      }
    };

const url =
  `${rewriteUrl()}front_api/service/all_service?service_type_id=`;
let one = `${url}1`;
let two = `${url}2`;
let three = `${url}3`;
let four = `${url}4`;
let five = `${url}5`;
let six = `${url}5`
const requestOne = axios.get(one);
const requestTwo = axios.get(two);
const requestThree = axios.get(three);
const requestFour = axios.get(four);
const requestFive = axios.get(five);
const requestSix = axios.get(six);

// Get single service
export const getSingleService = async (id) => axios.get(`${rewriteUrl()}front_api/service-types?filter[id]=${id}&include=services.customFields,services.variants.addons,services.variants.customFields`)
// GET_ALL_SERVICE
export const getAllServices = () => async (dispatch) => {
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  // try {
  const res = await axios
    .all([requestOne, requestTwo, requestThree, requestFour, requestFive, requestSix])
    .then(
      axios.spread((...responses) => {
        const responseOne = responses[0];
        const responseTwo = responses[1];
        const responesThree = responses[2];
        const responesFour = responses[3];
        const responesFive = responses[4];
        const responseSix = responses[5];
        // use/access the results
        console.log("all", {
          responseOne,
          responseTwo,
          responesThree,
          responesFour,
          responesFive,
          responseSix
        });
        dispatch({
          type: GET_ALL_SERVICE_SUCCESS,
          payload: {
            responseOne,
            responseTwo,
            responesThree,
            responesFour,
            responesFive,
          },
        });
      })
    )
    .catch((errors) => {
      // react on errors.
      console.log("error", errors);
      dispatch({
        type: GET_ALL_SERVICE_FAIL,
      });
    });
};

// Get getAllBanks
export const getAllBanks = () => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/common/bank/all`,
      config
    );
    dispatch({
      type: GET_ALL_BANKS_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_ALL_BANKS_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_ALL_BANKS_FAIL", error.message);
    dispatch({
      type: GET_ALL_BANKS_FAIL,
    });
    return error;
  }
};

// Get services
export const getServices = () => async (dispatch) => {
  const config = {
    headers: {
      "Content-Type": "application/json",
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/service/types`,
      // `${rewriteUrl()}front_api/service-types`,
      config
    );
    dispatch({
      type: GET_SERVICE_SUCCESS,
      payload: res?.data,
    });
    console.log("getServices", res?.data);
  } catch (error) {
    console.log("getServices error", error.message);
    dispatch({
      type: GET_SERVICE_FAIL,
    });
    return error;
  }
};

// Get services
export const getGiftCards = () => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/gift_card/list`,
      config
    );
    dispatch({
      type: GET_GIFT_CARDS_SUCCESS,
      payload: res?.data,
    });
    console.log("GET_GIFT_CARDS_SUCCESS", res?.data);
  } catch (error) {
    console.log("GET_GIFT_CARDS_FAIL", error.message);
    dispatch({
      type: GET_GIFT_CARDS_FAIL,
    });
    return error;
  }
};

// Get updataBankData
export const updataBankData = (values) => async (dispatch) => {
  dispatch({
    type: LOADING,
  });
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.post(
      `${rewriteUrl()}front_api/account/update_banking_data`,
      values,
      
      config
    );
    dispatch({
      type: UPDATE_BANK_DATA_SUCCESS,
      payload: res?.data,
    });
    toast.success(res.data.data)
    dispatch(loadUser());
    console.log("UPDATE_BANK_DATA_SUCCESS", res?.data);
  } catch (error) {
    console.log("UPDATE_BANK_DATA_FAIL", error.response.data.message);
    
    let payload = error.response.data.message;
    dispatch({
      type: UPDATE_BANK_DATA_FAIL,
      payload,
    });
    toast.error(payload)
    dispatch(loadUser());
    return error;
  }
};

// Get getwalletTopUp
export const getwalletTopUp =
  ({ amount, ref_number }, cb) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/account/wallet/credit`,
          {
            amount,
            ref_number,
          },
          config
        );
        dispatch({
          type: GET_WALLET_SUCCESS,
          payload: res?.data,
        });
        toast.success(res?.data?.message || "Successful")
      } catch (error) {
        console.log("getwalletTopUp error", error.message);
        dispatch({
          type: GET_WALLET_FAIL,
        });
        toast.error(error?.response?.data?.message || error?.message || "An error occured")
        return error;
      } finally {
        dispatch(loadUser())
        dispatch(allTransaction({ per_page: 5, page: 1 }));
        if (cb) {
          cb()
        }
      }
    };

// Get quick service
export const quickSerice =
  ({
    network,
    amount,
    phone_number,
    serviceKey,
    network_1,
    variant,
    phone_number_1,
    cable_tv_service,
    cable_tv_package,
    cable_tv_smart_card_number,
    electricity_disco,
    electricity_disco_id,
    electricity_amount,
    electricity_meter_number,
    electricity_meter_type,
    setModalState,
    networkValue,
    networkKey
  }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");
      dispatch({
        type: LOADING,
      });

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      const mode_of_payment = "wallet";

      try {
        const res = await axios.post(
          `${rewriteUrl()}front_api/service/order/${serviceKey}/bills`,
          {
            custom_fields: {
              network: network || networkKey,
              amount: amount,
              phone_number: phone_number || phone_number_1,
              // network_1: network_1,
              variant: variant,
              // phone_number_1: phone_number_1,
              cable_tv_service: cable_tv_service,
              cable_tv_package: cable_tv_package,
              cable_tv_smart_card_number: cable_tv_smart_card_number,
              electricity_disco: `${electricity_disco_id}`,
              electricity_amount: electricity_amount,
              electricity_meter_number: electricity_meter_number,
              electricity_meter_type: electricity_meter_type,
              network_value: networkValue,
              
            },
            mode_of_payment,
          },
          config
        );
        dispatch({
          type: QUICK_SERVICE_SUCCESS,
          payload: res?.data,
        });
        setModalState()
        console.log("quickSerice", res?.data);
      } catch (error) {
        // console.log("quickSerice error", error.response.data.message);
        let payload = error?.response?.data.message;

        dispatch({
          type: QUICK_SERVICE_FAIL,
          payload,
        });
        setModalState()
        return error;
      }
    };

// Get quick service
export const getVariant =
  ({ key }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      console.log("V key", key);

      try {
        const res = await axios.get(
          `${rewriteUrl()}front_api/service/single/${key}/variants`,
          config
        );
        dispatch({
          type: VARIANT_SUCCESS,
          payload: res?.data,
        });
        console.log("VARIANT", res?.data);
      } catch (error) {
        console.log("VARIANT error", error.message);
        dispatch({
          type: VARIANT_FAIL,
        });
        return error;
      }
    };

// Get all Transactions
export const allTransaction =
  ({ per_page, page, start_date, end_date, transaction_type }) =>
    async (dispatch) => {
      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      try {
        const res = await axios.get(
          `${rewriteUrl()}front_api/account/transaction/list?per_page=${per_page}&page=${page}&transaction_type=${transaction_type}`,
          config
        );
        // const array = Object.values(res?.data)
        const dataArray = Object.entries(res?.data)
          .filter(([key]) => key !== "meta")
          .map(([, value]) => value);
        dispatch({
          type: GET_ALL_TRANSACTION_SUCCESS,
          payload: dataArray,
        });
        console.log("GET_ALL_TRANSACTION_SUCCESS", res?.data);
      } catch (error) {
        console.log("GET_ALL_TRANSACTION_FAIL", error?.message);
        dispatch({
          type: GET_ALL_TRANSACTION_FAIL,
        });
        return error;
      }
    };

// Get SINGLE Transactions
export const singleTransactions = (id) => async (dispatch) => {
  const token = localStorage.getItem("access_token");

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  try {
    const res = await axios.get(
      `${rewriteUrl()}front_api/account/transaction/${id}/single`,
      config
    );
    const array = Object.values(res?.data);
    dispatch({
      type: GET_SINGLE_TRANSACTION_SUCCESS,
      payload: array,
    });
    console.log("GET_SINGLE_TRANSACTION_SUCCESS", array);
  } catch (error) {
    console.log("GET_SINGLE_TRANSACTION_FAIL", error?.message);
    dispatch({
      type: GET_SINGLE_TRANSACTION_FAIL,
    });
    return error;
  }
};
