import { PhoneEntity } from "../entities/PhoneEntity";

export const format = (phone: PhoneEntity): string => {
  const ddd = phone.number.slice(0, 2);
  const numberFirstPart = phone.number.slice(2, 7);
  const numberLastPart = phone.number.slice(7, 11);
  const countryCode = phone.countryCode;

  return `+${countryCode} (${ddd}) ${numberFirstPart}-${numberLastPart}`;
}