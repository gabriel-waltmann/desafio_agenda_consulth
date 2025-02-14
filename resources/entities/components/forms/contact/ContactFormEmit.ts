import { ContactFormEntity } from "./ContactFormEntity";

export interface ContactFormEmit { 
  (e: "submit", form: ContactFormEntity): void
};