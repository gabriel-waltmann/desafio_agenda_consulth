import { ContactFormAddressEntity } from "./ContactFormAddressEntity"
import { ContactFormPhoneEntity } from "./ContactFormPhoneEntity"

export interface ContactFormEntity {
  name: string,
  email: string,
  address: ContactFormAddressEntity,
  phones: Array<ContactFormPhoneEntity>
}