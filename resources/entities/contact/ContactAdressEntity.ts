import { AddressEntity } from "../AddressEntity";
import { ContactEntity } from "./ContactEntity";

export interface ContactAddressEntity {
  id: number;
  contact_id: number;
  address_id: number;

  // relations
  contact?: ContactEntity;
  address?: AddressEntity;
}