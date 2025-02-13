<template>
  <main class="w-full h-full flex flex-col gap-1 p-2">
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
import ContactList from "../components/contact/contact-list.vue";
import { ContactEntity } from "../entities/contact/ContactEntity";
import { ref, onMounted, Ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

async function goToContactNew() {
  try {
    await router.push("/new");
  } catch (error: any) {
    console.error(error);
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

// async function createContact() {
//   try {
//     const { status, data } = await axios.post("/api/contact", {
//       name: "Novo contato",
//       email: "novocontato@gmail.com",
//       phones: [
//         { number: "47996153008", countryCode: "55" },
//         { number: "11996153009", countryCode: "55" }
//       ],
//     });

//     if (status !== 200) throw new Error("Não foi possível criar o contato");

//     contacts.value.push(data);
//   } catch (error) {
//     console.error(error);
//   }
// }

async function goToContact(contact: ContactEntity): Promise<void> {
  try {
    const { status, data } = await axios.delete(`/api/contact/${contact.id}`);

    if (status !== 200) throw new Error("Não foi possível deletar o contato");

    contacts.value = contacts.value.filter((item) => item.id !== contact.id);
  } catch (error) {
    console.error(error);
  }
}

const contacts: Ref<ContactEntity[]> = ref([
  // { 
  //   id: 1, 
  //   name: "contact 1", 
  //   email: "contact1@gmail.com", 
  //   phones: [{ 
  //     id: 1, 
  //     contact_id: 1, 
  //     phone_id: 1, 
  //     phone: { 
  //       id: 1,
  //       number: "47996153009",
  //       countryCode: "55"
  //     },
  //   }]
  // },
])

onMounted(async () => {
  await handleContacts();
})
</script>