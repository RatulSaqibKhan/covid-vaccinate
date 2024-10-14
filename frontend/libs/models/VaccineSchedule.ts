import { VaccineCenter } from "./VaccineCenter";

export interface VaccineSchedule {
  id?: number;
  vaccine_center_id: number;
  scheduled_date: string;
  slots_filled: number;
  vaccine_center?: VaccineCenter;
}
