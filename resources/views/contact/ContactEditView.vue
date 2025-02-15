<template>
  <page-container>
    <header class="flex flex-row justify-between">
      <h1 class="text-2xl">Editar contato</h1>
    </header>

    <main-container>
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
    </main-container>
  </page-container>
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
import { ContactFormAddressEntity } from '../../entities/components/forms/contact/ContactFormAddressEntity';
import { ContactFormPhoneEntity } from '../../entities/components/forms/contact/ContactFormPhoneEntity';
import MainContainer from '../../components/containers/MainContainerComponent.vue';
import PageContainer from "../../components/containers/PageContainerComponent.vue";

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
  const contactPhones = contact.phones ?? [];
  const phones: ContactFormPhoneEntity[] = contactPhones.map(({phone}) => ({
    countryCode: phone?.countryCode ?? "55",
    number: phoneNumberUtil.format(phone?.number ?? "")
  }));

  const contactAddress = contact.address?.address;
  const address: ContactFormAddressEntity = {
    id: contact.address?.id,
    country: contactAddress?.country ?? "Brasil",
    state: contactAddress?.state ?? "",
    city: contactAddress?.city ?? "",
    neighborhood: contactAddress?.neighborhood ?? "",
    address: contactAddress?.address ?? "",
    zipCode: contactAddress?.zipCode ?? "",
  };

  return {
    name: contact.name,
    email: contact.email,
    address,
    phones,
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