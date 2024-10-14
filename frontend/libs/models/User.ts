import { VaccineCenter } from "./VaccineCenter";

export interface User {
  id?: number;
  name: string;
  email: string;
  nid: string;
  phone: string;
  vaccine_center_id: string;
  registered_at?: string | Date;
  status?: string;
  scheduled_date?: string;
  created_at?: string | Date;
  updated_at?: string | Date;
  vaccine_center?: VaccineCenter;
}