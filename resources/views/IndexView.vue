<template>
  <main class="w-full h-full flex flex-col gap-2 p-2">
    <header class="flex flex-row justify-between">
      <h1 class="text-2xl">Contatos</h1>

      <nav class="flex flex-row gap-2">
        <button @click="goToContactNew" class="border py-1 px-2 rounded-2xl">
          + Contato
        </button>
      </nav>
    </header>

    <contact-list 
      :contacts="contacts" 
      @go-to-contact="goToContact" 
    />
  </main>
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