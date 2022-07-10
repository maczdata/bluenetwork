import React from "react";

const List = ({color, data, handleClick}) => {
  return (
    <>
      <li
        key={data.id}
        style={color}
        onMouseOver={() => handleClick(data.id)}
      >
        <img src={data.icon} alt='' />
      </li>
    </>
  );
};

export default List;
