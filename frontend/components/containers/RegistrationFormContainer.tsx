"use client";
import React, { useEffect, useState } from "react";
import Title from "antd/es/typography/Title";
import RegistrationForm from "../forms/RegistrationForm";
import { VaccineCenter } from "@/libs/models/VaccineCenter";
import CommonResponse from "@/libs/api-response-interfaces/CommonResponse";
import { createBackendService } from "@/libs/services/BackendApiService";
import { BackendServiceApiResponseInterface } from "@/libs/api-response-interfaces/BackendServiceApiResponseInterface";
import { VaccineCenterListApiResponse } from "@/libs/api-response-interfaces/VaccineCenterListApiResponse";
import { VaccineCenterListRequestPayload } from "@/libs/api-request-interfaces/VaccineCenterListRequestPayload";
import { notification } from "antd";

const RegistrationFormContainer: React.FC = () => {
  const [vaccineCenters, setVaccineCenters] = useState<VaccineCenter[]>([]);
  const [filter, setFilter] = useState<string>("");
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    fetchVaccineCenters(filter);
  }, [filter]);

  const fetchVaccineCenters = async (filter: string) => {
    setIsLoading(true);
    const queries: VaccineCenterListRequestPayload = {
      limit: 10,
      page: 1,
      search: filter || undefined,
    };
    try {
      const response: CommonResponse<
        BackendServiceApiResponseInterface<VaccineCenterListApiResponse>
      > = await createBackendService().getVaccineCenters(queries);

      setVaccineCenters(response.data.data.items);
    } catch (error) {
      const message = error?.response?.data?.message;
      const reason = error?.response?.data?.reason;

      notification.error({
        message: message || "Something went wrong",
        description: reason,
      });

      console.error("Vaccine Center list fetch error:", error);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <>
      <div className="flex justify-center items-center pb-4">
        <Title level={2} className="mb-0">
          COVID Vaccine Registration
        </Title>
      </div>
      <div>
        <RegistrationForm
          fetchVaccineCenters={fetchVaccineCenters}
          setVaccineCenterFilter={setFilter}
          vaccineCenters={vaccineCenters}
          vaccineCenterFetchLoading={isLoading}
        />
      </div>
    </>
  );
};

export default RegistrationFormContainer;
