export interface BackendServiceApiResponseInterface<T> {
  code: number;
  message: string;
  status: string;
  data: T;
}