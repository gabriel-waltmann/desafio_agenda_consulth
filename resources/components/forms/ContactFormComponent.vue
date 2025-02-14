<template>
  <form @submit="handleSubmit" class="flex flex-col h-full gap-6 py-2 ">
    <div class="flex-1 flex flex-col gap-6">
      <section class="flex flex-col gap-2">
        <input 
          placeholder="Nome*" 
          class="border border-gray-200 rounded-xl p-2" 
          type="text" 
          id="nameInput"
          v-model="form.name"
        >
  
        <input 
          placeholder="E-mail*" 
          class="border border-gray-200 rounded-xl p-2" 
          type="email" 
          id="emailInput"
          v-model="form.email"
        >
      </section>
  
      <section class="flex flex-col gap-2">
        <h2 class="text-base font-semibold">Endereço</h2>

        <input 
          placeholder="País" 
          class="border border-gray-200 rounded-xl p-2" 
          type="text"
          v-model="form.address.country"
        >
        
        <input 
          placeholder="Estado" 
          class="border border-gray-200 rounded-xl p-2" 
          type="text"
          v-model="form.address.state"
        >

        <input 
          placeholder="Cidade" 
          class="border border-gray-200 rounded-xl p-2" 
          type="text"
          v-model="form.address.city"
        >

        <input 
          placeholder="Endereço" 
          class="border border-gray-200 rounded-xl p-2" 
          type="text"
          v-model="form.address.address"
        >

        <input 
          placeholder="CEP" 
          class="border border-gray-200 rounded-xl p-2" 
          type="text"
          v-model="form.address.zipCode"
        >
      </section>

      <section class="flex flex-col gap-2">
        <div class="w-full flex flex-row flex-nowrap items-start">
          <h2 class="text-base font-semibold flex-1">Telefone</h2>

          <nav class="flex flex-row gap-2">
            <button type="button" @click="addPhone" class="border rounded-full flex justify-center items-center h-8 w-8">
              <img src="../../assets/icons/plus.svg" class="w-6 h-6" alt="add button">
            </button>
          </nav>
        </div>

        <ul class="flex flex-col gap-2">
          <li class="border border-gray-200 rounded-xl p-2 flex flex-row gap-2 items-center w-full" v-for="(phone, index) of form.phones">
              <input
                placeholder="Prefixo"
                class="border border-gray-200 rounded-xl p-2 w-18"
                type="text"
                v-model="phone.countryCode"
                :id="`pidixInput${index}`"
              />

              <input 
                placeholder="Número" 
                class="border border-gray-200 rounded-xl p-2 w-[75%]" 
                type="text"
                @input="phone.number = phoneNumberUtil.format(phone.number)"
                v-model="phone.number"
                :id="`phoneInput${index}`"
              />

              <nav>
                <button type="button" class="border h-8 w-8 rounded-full flex justify-center items-center">
                  <img src="../../assets/icons/trash.svg" class="w-6 h-6" alt="add button">
                </button>
              </nav>
          </li>
        </ul>
      </section>
    </div>

    <nav class="flex flex-row gap-2 w-full">
      <button type="submit" class="border py-1 px-2 rounded-2xl w-full">
        + Contato
      </button>
    </nav>
  </form>
</template>

<script lang="ts">
export default {};
</script>

<script lang="ts" setup>
import { onMounted, Ref, ref } from 'vue';
import * as emailUtil from "../../utils/email";
import * as phoneNumberUtil from "../../utils/phone";
import { ContactFormEmit } from '../../entities/components/forms/contact/ContactFormEmit';
import { ContactFormEntity } from '../../entities/components/forms/contact/ContactFormEntity';



const emit: ContactFormEmit = defineEmits(["submit"]);

const form: Ref<ContactFormEntity> = ref({
  name: "",
  email: "",
  address: {
    country: "Brasil",
    state: "",
    city: "",
    neighborhood: "",
    address: "",
    zipCode: "",
  },
  phones: [{
    countryCode: "55",
    number: ""
  }],
})

function emitSubmit(form: ContactFormEntity) {
  emit("submit", form);
}

function addPhone(): void {
  const voidPhoneIndex = form.value.phones.findIndex((phone) => !!(
    !phone.countryCode.length || 
    !phone.number.length
  ));

  // redirect to phone input if void
  if (voidPhoneIndex !== -1) {
    document.getElementById(`phoneInput${voidPhoneIndex}`)?.focus();
    return;
  }

  form.value.phones.push({
      countryCode: "55",
      number: ""
    });
}

async function handleSubmit(e: Event): Promise<void> {
  e.preventDefault();

  if (!form.value.name.length) {
    document.getElementById("nameInput")?.focus();
    return;
  }

  if (!form.value.email.length || !emailUtil.validate(form.value.email)) {
    document.getElementById("emailInput")?.focus();
    return;
  }

  for (const phoneIndex in form.value.phones) {
    const phone = form.value.phones[phoneIndex];
    
    if (!phone.countryCode.length || !phone.number.length) {
      document.getElementById(`phoneInput${phoneIndex}`)?.focus();

      return;
    }

    if (!phoneNumberUtil.validate(phone.number)) {
      document.getElementById(`phoneInput${phoneIndex}`)?.focus();
      return;
    }
  }  

  emitSubmit(form.value);
}
</script>