import { ContactFormEntity } from "./ContactFormEntity";

export interface ContactFormEmit { 
  (e: "cancel"): void
  (e: "submit", form: ContactFormEntity): void
};