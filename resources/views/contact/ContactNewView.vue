<template>
  <div class="w-full h-full flex flex-col gap-6 p-2">
    <header class="flex flex-row justify-between">
      <h1 class="text-2xl">Novo contato</h1>
    </header>

    <main-container>
      <contact-form 
        :form="form" 
        @submit="handleSubmit"
        @cancel="goToIndexPage"
      />
    </main-container>
  </div>
</template>

<script lang="ts">
export default {};
</script>

<script lang="ts" setup>
import axios from 'axios';
import ContactForm from '../../components/forms/ContactFormComponent.vue';
import { ContactFormEntity } from '../../entities/components/forms/contact/ContactFormEntity';
import * as phoneNumberUtil from "../../utils/phone";
import { useRouter } from 'vue-router';
import { ref, Ref } from 'vue';
import MainContainer from '../../components/containers/MainContainerComponent.vue';

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

const router = useRouter();

async function handleSubmit(form: ContactFormEntity) {
  try {
    const { status, data } = await axios.post("/api/contact", {
      name: form.name,
      email: form.email,
      phones: form.phones.map((phone) => ({
        number: phoneNumberUtil.parse(phone.number),
        countryCode: phone.countryCode
      })),
      address: form.address
    });

    if (status !== 201) throw new Error("Não foi possível criar o contato");

    await router.push({ name: "contact", params: { id: data.id } });
  } catch (error) {
    console.error(error);

    alert("Não foi possível criar o contato. Por favor, tente novamente em alguns instantes.");
  }
}

async function goToIndexPage() {
  try {
    await router.push({ name: "index" });
  } catch (error: any) {
    console.error(error);

    alert("Não foi possível abrir a página. Por favor, tente novamente em alguns instantes.");
  }
}
</script>