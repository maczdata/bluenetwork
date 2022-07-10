import React from "react";
import "./index.css";
import IMG1 from "../../assets/search-icon.svg";

const Search = () => {
  return (
    
      <div className='search-input'>
        <span className='icon'>
          <img src={IMG1} alt='' />
        </span>

        <input type='text'  placeholder="Search"/>
        <span className='btn-search'>
          <button>Search</button>
        </span>
      </div>
   
  );
};

export default Search;
