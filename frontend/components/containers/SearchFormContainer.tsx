"use client";
import React from "react";
import Title from "antd/es/typography/Title";
import SearchForm from "../forms/SearchForm";

const SearchFormContainer: React.FC = () => {

  return (
    <>
      <div className="flex justify-center items-center pb-4">
        <Title level={2} className="mb-0">
          Search for Covid Vaccination Registration
        </Title>
      </div>
      <div>
        <SearchForm />
      </div>
    </>
  );
};

export default SearchFormContainer;
