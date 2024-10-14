export default interface CommonResponse<T> {
  status: number;
  data: T;
  headers?: Record<string, any>;
  responseTime?: number | null;
};