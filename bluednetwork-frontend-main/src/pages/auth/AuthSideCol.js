import React from "react";
import IMG1 from "../../assets/Web-design.svg";
import FormSideSlider from "../../components/form/FormSideSlider";

const AuthSideCol = () => {
  return (
    <div>
      <div>
        <img src={IMG1} alt='img' />
      </div>
      <div>
        <FormSideSlider />
      </div>
    </div>
  );
};

export default AuthSideCol;
