import { User } from "./User";
import { VaccineSchedule } from "./VaccineSchedule";

export interface VaccineCenter {
  id?: number;
  name: string;
  address: string;
  daily_capacity: number;
  available_date: string | Date;
  created_at?: string | Date;
  updated_at?: string | Date;
  users?:  User[];
  vaccine_schedules?: VaccineSchedule[];
}