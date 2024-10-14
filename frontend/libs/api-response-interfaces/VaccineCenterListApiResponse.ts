import { VaccineCenter } from "../models/VaccineCenter";
import { PaginatorLinksData, PaginatorMetaData } from "./PaginatorData";

export interface VaccineCenterListApiResponse {
  items: VaccineCenter[];
  links?: PaginatorLinksData;
  meta?: PaginatorMetaData;
}