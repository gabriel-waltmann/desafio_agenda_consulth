import { ContactAddressEntity } from "./ContactAdressEntity";
import { ContactPhoneEntity } from "./ContactPhoneEntity";

export interface ContactEntity {
  id: number;
  name: string;
  email: string;

  // relations
  phones?: Array<ContactPhoneEntity>;
  address?: ContactAddressEntity;
}