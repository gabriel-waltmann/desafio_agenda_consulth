<template>
  <div class="w-full h-full flex flex-col gap-6 p-2">
    <header class="flex flex-row justify-between">
      <h1 class="text-2xl">Editar contato</h1>
    </header>

    <main class="flex flex-col gap-2 flex-1">
      <div class="flex-1 flex flex-col gap-2" v-if="contact">
        <contact-form 
          :form="form" 
          @submit="form => handleSubmit(form)" 
          @cancel="goToContactPage"
        />
      </div>

      <template v-else>
        <h2 class="text-base text-center">Erro ao encontrar contato</h2>
      </template>
    </main>
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
import { useRoute, useRouter } from 'vue-router';
import { onMounted, ref, Ref } from 'vue';
import { ContactEntity } from '../../entities/contact/ContactEntity';

const route = useRoute();

const router = useRouter();

const contact: Ref<ContactEntity | null> = ref(null);

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

async function handleSubmit(form: ContactFormEntity): Promise<void> {
  try {
    if (!contact.value) throw new Error("Contato não encontrado");

    const { status, data } = await axios.put(`/api/contact/${contact.value.id}`, {
      name: form.name,
      email: form.email,
      phones: form.phones.map((phone) => ({
        number: phoneNumberUtil.parse(phone.number),
        countryCode: phone.countryCode
      })),
      address: form.address
    });

    if (status !== 200) throw new Error("Não foi possível criar o contato");

    await router.push({ name: "contact", params: { id: data.id } });
  } catch (error) {
    console.error(error);

    alert("Não foi possível criar o contato. Por favor, tente novamente em alguns instantes.");
  }
}

async function handleContact(contactId: string): Promise<void> {
  try {
    const { status, data } = await axios.get("/api/contact/" + contactId);

    if (status !== 200) throw new Error("Não foi possível encontrar o contato");

    contact.value = data;
  } catch (error) {
    console.error(error);

    alert("Não foi possível encontrar o contato. Por favor, tente novamente em alguns instantes.");
  }
} 

function getContactForm(contact: ContactEntity): ContactFormEntity {
  return {
    name: contact.name,
    email: contact.email,
    address: {
      country: "Brasil",
      state: "",
      city: "",
      neighborhood: "",
      address: "",
      zipCode: "",
    },
    phones: contact.phones?.map(({phone}) => ({
      countryCode: phone?.countryCode ?? "55",
      number: phoneNumberUtil.format(phone?.number ?? "")
    })) ?? []
  }
}

async function goToContactPage(): Promise<void> {
  try {
    if (!contact.value) throw new Error("Contato não encontrado");

    await router.push({ name: "contact", params: { id: contact.value.id } });
  } catch (error: any) {
    console.error(error);
    
    alert("Não foi possível abrir a página. Por favor, tente novamente em alguns instantes.");
  }
}

onMounted(async () => {
  await handleContact(route.params.id as string);

  if (!contact.value) return;

  form.value = getContactForm(contact.value);
})
</script>