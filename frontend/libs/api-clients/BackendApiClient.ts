import axios from "axios";
import BaseApiClient from "./BaseApiClient";

const getClient = (options: Record<string, unknown> = {}) => {
  const defaults = {
    baseURL: process.env.NEXT_PUBLIC_BACKEND_SERVICE_BASE_PROXY_URI,
    timeout: 300000,
  };

  if (!options.headers) {
    options.headers = {
      Accept: "application/json",
      "Content-type": "application/json;charset=utf-8",
    };
  }

  const mergedOptions = Object.assign({}, axios.defaults, defaults, options);
  const client = axios.create(mergedOptions);

  return client;
};

export class BackendApiClient extends BaseApiClient {
  constructor(options: Record<string, unknown> = {}) {
    const client = getClient(options);
    super(client);
  }
}