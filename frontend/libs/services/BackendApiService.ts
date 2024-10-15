import { BackendApiClient } from "../api-clients/BackendApiClient";
import BaseApiClient from "../api-clients/BaseApiClient";
import { UserRegistrationRequestPayload } from "../api-request-interfaces/UserRegistrationRequestPayload";
import { VaccineCenterListRequestPayload } from "../api-request-interfaces/VaccineCenterListRequestPayload";
import { BackendServiceApiResponseInterface } from "../api-response-interfaces/BackendServiceApiResponseInterface";
import { VaccineCenterListApiResponse } from "../api-response-interfaces/VaccineCenterListApiResponse";
import { settings } from "../constants/settings";
import { User } from "../models/User";

class BackendService {
  private apiClient: BaseApiClient;

  constructor(apiClient: BaseApiClient) {
    this.apiClient = apiClient;
  }

  async userSearch(nid: string) {
    return await this.apiClient.get<BackendServiceApiResponseInterface<User | null>>(`/v1/search/${nid}`);
  }

  async getVaccineCenters(queries: VaccineCenterListRequestPayload = {}) {
    queries.page = queries.page || settings.listOptions.currentPage;
    queries.limit = queries.limit || settings.listOptions.limit;

    return await this.apiClient.get<BackendServiceApiResponseInterface<VaccineCenterListApiResponse>>("/v1/vaccine-centers", queries);
  }

  async registerUser(user: UserRegistrationRequestPayload) {
    return await this.apiClient.post<BackendServiceApiResponseInterface<User>>("/v1/register", user);
  }
}

function createBackendService<T>(options: Record<string, T> = {}) {
  return new BackendService(new BackendApiClient(options));
}

export { BackendService, createBackendService };
