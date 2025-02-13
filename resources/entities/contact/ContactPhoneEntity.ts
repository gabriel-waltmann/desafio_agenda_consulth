import { PhoneEntity } from "../PhoneEntity";
import { ContactEntity } from "./ContactEntity";

export interface ContactPhoneEntity {
  id: number;
  contact_id: number;
  phone_id: number;

  // relations
  contact?: ContactEntity;
  phone?: PhoneEntity;
}