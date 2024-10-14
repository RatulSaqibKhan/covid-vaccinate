"use client";
import React, { useCallback, useState } from "react";
import { Col, Form, Input, notification, Row, Select, Spin } from "antd";
import FormButtonSection from "../layout/FormButtomSection";
import CommonResponse from "@/libs/api-response-interfaces/CommonResponse";
import { createBacckendService } from "@/libs/services/BackendApiService";
import { BackendServiceApiResponseInterface } from "@/libs/api-response-interfaces/BackendServiceApiResponseInterface";
import { User } from "@/libs/models/User";
import { UserRegistrationRequestPayload } from "@/libs/api-request-interfaces/UserRegistrationRequestPayload";
import debounce from "lodash/debounce";
import { VaccineCenter } from "@/libs/models/VaccineCenter";
const { Option } = Select;

export interface ResistrationFormProps {
  fetchVaccineCenters: (filter: string) => Promise<void>;
  setVaccineCenterFilter: (filter: string) => void;
  vaccineCenters: VaccineCenter[];
  vaccineCenterFetchLoading: boolean;
}

const RegistrationForm: React.FC<ResistrationFormProps> = (
  props: ResistrationFormProps
) => {
  const {
    fetchVaccineCenters,
    setVaccineCenterFilter,
    vaccineCenters,
    vaccineCenterFetchLoading,
  } = props;

  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);

  // Debounced fetch for vaccine centers to reduce API calls
  const debouncedFetchVaccineCenters = useCallback(() => {
    return debounce(fetchVaccineCenters, 4000);
  }, [fetchVaccineCenters]);

  const handleVaccineCenterSearch = (value: string) => {
    setVaccineCenterFilter(value);
    debouncedFetchVaccineCenters();
  };

  // Custom validation rule for phone field
  const validatePhone = (_, value: string) => {
    const phone = value ? `880${value}` : null;

    let isValid = true;
    const message = "The phone is invalid";
    const regex = /^880+([0-9\s\-\+\(\)]*)$/;
    if (phone && phone.length !== 13) {
      isValid = false;
    } else if (phone && regex.exec(phone) === null) {
      isValid = false;
    }
    if (isValid) {
      return Promise.resolve();
    }
    return Promise.reject(new Error(message));
  };

  const submitForm = async (values: UserRegistrationRequestPayload) => {
    setLoading(true);
    const phone = `880${values.phone}`;

    const userData: UserRegistrationRequestPayload = {
      name: values.name,
      email: values.email,
      nid: values.nid,
      vaccine_center_id: values.vaccine_center_id,
      phone,
    };

    try {
      const response: CommonResponse<BackendServiceApiResponseInterface<User>> =
        await createBacckendService().registerUser(userData);

      notification.success({ message: response.data.message });
    } catch (error) {
      if (error.type === "validation-errors") {
        const serverErrors = error.validationErrors || {};
        const formattedErrors = Object.keys(serverErrors).map((name) => ({
          name,
          errors: serverErrors[name],
        }));
        form.setFields(formattedErrors);
      }

      const message = error?.error?.response?.data?.message;
      const reason = error?.error?.response?.data?.reason;
      notification.error({
        message: message || "Something went wrong",
        description: reason,
      });
    } finally {
      setLoading(false);
    }
  };

  const renderOptionLabel = (vaccineCenter: VaccineCenter) => (
    <div>
      <strong>{vaccineCenter.name}</strong>
      <br />
      <small>{vaccineCenter.address}</small>
    </div>
  );

  return (
    <Form
      form={form}
      name="RegistrationForm"
      className="w-full m-auto mt-6"
      layout="vertical"
      autoComplete="off"
      size={"large"}
      onFinish={submitForm}
    >
      <Row gutter={24}>
        <Col span={12}>
          <Form.Item
            label="Name"
            name="name"
            className="!mb-2"
            rules={[{ required: true, message: "Please input your name!" }]}
          >
            <Input placeholder="Write full name" />
          </Form.Item>
        </Col>

        <Col span={12}>
          <Form.Item
            label="Email"
            name="email"
            className="!mb-2"
            rules={[
              { required: true, message: "Please input your email!" },
              { type: "email", message: "Please enter a valid email" },
            ]}
          >
            <Input placeholder="Write email" />
          </Form.Item>
        </Col>

        <Col span={12}>
          <Form.Item
            label="NID"
            name="nid"
            className="!mb-2"
            rules={[
              { required: true, message: "Please input your NID!" },
              { max: 20, message: "NID must be less or equal to 20 characters" },
            ]}
          >
            <Input type="number" placeholder="Write your NID" maxLength={20} />
          </Form.Item>
        </Col>

        <Col span={12}>
          <Form.Item
            label="Phone"
            name="phone"
            className="!mb-2"
            rules={[
              { required: true, message: "Please input your phone number!" },
              { min: 10, message: "Phone number must be of 13 characters" },
              { max: 10, message: "Phone number must be of 13 characters" },
              { validator: validatePhone },
            ]}
          >
            <Input
              type="number"
              addonBefore="880"
              placeholder="Write phone e.g. 1XXXXXXXXX"
              maxLength={10}
            />
          </Form.Item>
        </Col>

        <Col span={12}>
          <Form.Item
            label="Vaccine Center"
            name="vaccine_center_id"
            rules={[{ required: true, message: "Please select a role" }]}
          >
            <Select
              showSearch
              placeholder="Select a Vacchine Center"
              filterOption={false} // Disable default filtering, use API
              onSearch={handleVaccineCenterSearch}
              notFoundContent={
                vaccineCenterFetchLoading ? <Spin size="small" /> : null
              }
              optionLabelProp="label"
            >
              {vaccineCenters.map((center: VaccineCenter) => (
                <Option
                  key={center.id}
                  value={center.id}
                  label={center.name}
                >
                  {renderOptionLabel(center)}
                </Option>
              ))}
            </Select>
          </Form.Item>
        </Col>
      </Row>

      <FormButtonSection loading={loading} form={form} />
    </Form>
  );
};

export default RegistrationForm;
