<template>
  <page-container class="flex flex-col gap-4">
    <header class="flex flex-row justify-between w-full">
      <primary-text class="flex-1">
        <span>Agenda de contatos</span>
      </primary-text>

      <nav class="flex flex-row gap-2">
        <primary-button @click="goToContactNew">
          <span>+ CONTATO</span>
        </primary-button>
      </nav>
    </header>

    <main-container>
      <section class="w-full">
        <contact-list
          :contacts="contacts"
          @go-to-contact="goToContact"
        />
      </section>
    </main-container>
  </page-container>
</template>

<script lang="ts">
export default {};
</script>

<script lang="ts" setup>
import ContactList from "../components/lists/contact/ContactListComponent.vue";
import { ContactEntity } from "../entities/contact/ContactEntity";
import { ref, onMounted, Ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import MainContainer from "../components/containers/MainContainerComponent.vue";
import PageContainer from "../components/containers/PageContainerComponent.vue";
import PrimaryButton from "../components/buttons/PrimaryButtonComponent.vue";
import PrimaryText from "../components/texts/PrimaryTextComponent.vue";

const router = useRouter();

async function goToContactNew() {
  try {
    await router.push({ name: "contact-new" });
  } catch (error: any) {
    console.error(error);

    alert("Não foi possível abrir a página. Por favor, tente novamente em alguns instantes.");
  }
}

async function handleContacts(): Promise<void> {
  try {
    const { status, data } = await axios.get("/api/contacts");
  
    if (status !== 200) throw new Error("Não foi possível carregar os contatos");

    contacts.value = data;
  } catch (error) {
    console.error(error);

    contacts.value = [];
  }
}

async function goToContact(contact: ContactEntity): Promise<void> {
  try {
    await router.push({ name: "contact", params: { id: contact.id } });
  } catch (error) {
    console.error(error);

    alert("Não foi possível carregar o contato. Por favor, tente novamente em alguns instantes.");
  }
}

const contacts: Ref<ContactEntity[]> = ref([])

onMounted(async () => {
  await handleContacts();
})
</script>