import { AxiosInstance, AxiosRequestConfig } from "axios";
import CommonResponse from "../api-response-interfaces/CommonResponse";

const commonSuccessResponse = (response: Record<string, any>): Promise<any> => {
  if (response?.data?.status || response?.data?.status === "success") {
    return Promise.resolve({
      status: response.status,
      data: response.data,
      headers: response.headers,
      responseTime: response.duration,
    });
  }

  return Promise.reject({
    type: "invalid-response",
    message: "Response was invalid.",
    response: response,
    responseTime: response.duration,
  });
};

const commonErrorResponse = (error) => {
  if (error.response && error.response.status == 422) {
    let validationErrors;
    if (error.response.data.errors) {
      validationErrors = error.response.data.errors;
    } else if (
      error.response.data.response &&
      error.response.data.response.errors
    ) {
      validationErrors = error.response.data.response.errors;
    }

    return Promise.reject({
      type: "validation-errors",
      message: "Validation Errors",
      error: error,
      validationErrors: validationErrors,
    });
  }

  let msg = "An error occured.";
  if (
    error &&
    error.response &&
    error.response.data &&
    error.response.data.message
  ) {
    msg = error.response.data.message;
  }

  return Promise.reject({
    type: "error",
    message: msg,
    error: error,
  });
};

export default abstract class BaseApiClient {
  private client: AxiosInstance;

  constructor(client: AxiosInstance) {
    this.client = client;
  }

  async get<T = any>(
    url: string,
    queries: Record<any, any> = {},
    conf: AxiosRequestConfig<any> = {}
  ): Promise<CommonResponse<T>> {
    conf.params = queries;

    return this.client
      .get(url, conf)
      .then((response) => commonSuccessResponse(response))
      .catch((error) => commonErrorResponse(error));
  }

  async delete<T = any>(
    url: string,
    data: Record<any, any> = {},
    conf: AxiosRequestConfig<any> = {}
  ): Promise<CommonResponse<T>> {
    conf.data = data;
    return this.client
      .delete(url, conf)
      .then((response) => commonSuccessResponse(response))
      .catch((error) => commonErrorResponse(error));
  }

  async head<T = any>(
    url: string,
    conf: AxiosRequestConfig<any> = {}
  ): Promise<CommonResponse<T>> {
    return this.client
      .head(url, conf)
      .then((response) => commonSuccessResponse(response))
      .catch((error) => commonErrorResponse(error));
  }

  async post<T = any>(
    url: string,
    data: Record<any, any> = {},
    conf: AxiosRequestConfig<any> = {}
  ): Promise<CommonResponse<T>> {
    return this.client
      .post(url, data, conf)
      .then((response) => commonSuccessResponse(response))
      .catch((error) => commonErrorResponse(error));
  }

  async put<T = any>(
    url: string,
    data: Record<any, any> = {},
    conf: AxiosRequestConfig<any> = {}
  ): Promise<CommonResponse<T>> {
    return this.client
      .put(url, data, conf)
      .then((response) => commonSuccessResponse(response))
      .catch((error) => commonErrorResponse(error));
  }

  async patch<T = any>(
    url: string,
    data: Record<any, any> = {},
    conf: AxiosRequestConfig<any> = {}
  ): Promise<CommonResponse<T>> {
    return this.client
      .patch(url, data, conf)
      .then((response) => commonSuccessResponse(response))
      .catch((error) => commonErrorResponse(error));
  }
}
