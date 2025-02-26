<template>
  <page-container>
    <header class="flex flex-row justify-between mb-4">
      <primary-text>
        <span>Detalhes do contato</span>
      </primary-text>
    </header>

    <main-container>
      <div class="flex-1 flex flex-col gap-2" v-if="contact">
        <section>
          <article class="flex flex-col gap-2">
            <h2>Nome: {{ contact.name }}</h2>

            <p>E-mail: {{ contact.email }}</p>
          </article>
        </section>
  
        <section>
          <h2 class="text-base font-semibold">Telefones</h2>

          <ul class="flex flex-col gap-2">
            <li v-for="({ phone }) of contact.phones">
              <p v-if="phone">+{{ phone.countryCode }} {{ phoneNumberUtil.format(phone.number) }}</p>
            </li>
          </ul>
        </section>

        <section v-if="contact.address?.address">
          <article>
            <h2 class="text-base font-semibold">Endereço</h2>

            <p>País: {{ contact.address.address.country }} </p>
            <p>Estado: {{ contact.address.address.state }} </p>
            <p>Cidade: {{ contact.address.address.city }} </p>
            <p>Bairro: {{ contact.address.address.neighborhood }} </p>
            <p>CEP: {{ contact.address.address.zipCode }} </p>
            <p>Endereço: {{ contact.address.address.address }} </p>
          </article>
        </section>
      </div>
  
      <template v-else>
        <h2 class="text-base text-center">Erro ao encontrar contato</h2>
      </template>

      <nav class="flex flex-col md:flex-row md:justify-end gap-2 w-full">
        <primary-button @click="goToIndexPage">
          <span>VOLTAR</span>
        </primary-button>

        <primary-button @click="goToContactEditPage">
          EDITAR
        </primary-button>

        <primary-button @click="deleteContact">
          DELETAR
        </primary-button>
      </nav>
    </main-container>
  </page-container>
</template>

<script lang="ts">
export default {};
</script>

<script lang="ts" setup>
import axios from 'axios';
import * as phoneNumberUtil from "../../utils/phone";
import { useRoute, useRouter } from 'vue-router';
import { onMounted, Ref, ref } from 'vue';
import { ContactEntity } from '../../entities/contact/ContactEntity';
import PrimaryButton from '../../components/buttons/PrimaryButtonComponent.vue';
import PrimaryText from "../../components/texts/PrimaryTextComponent.vue";
import MainContainer from '../../components/containers/MainContainerComponent.vue';
import PageContainer from "../../components/containers/PageContainerComponent.vue";

const route = useRoute();

const router = useRouter();

const contact: Ref<ContactEntity | null> = ref(null);

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

async function goToIndexPage() {
  try {
    await router.push({ name: "index" });
  } catch (error: any) {
    console.error(error);

    alert("Não foi possível abrir a página. Por favor, tente novamente em alguns instantes.");
  }
}

async function deleteContact() {
  try {
    if (!contact.value) return;

    const { status } = await axios.delete(`/api/contact/${contact.value.id}`);

    if (status !== 200) throw new Error("Não foi possível deletar o contato");

    await router.push({ name: "index" });
  } catch (error) {
    console.error(error);

    alert("Não foi possível deletar o contato. Por favor, tente novamente em alguns instantes.");
  }
}

async function goToContactEditPage() {
  try {
    await router.push({ name: "contact-edit", params: { id: contact.value?.id } });
  } catch (error: any) {
    console.error(error);

    alert("Não foi possível abrir a página. Por favor, tente novamente em alguns instantes.");
  }
}

onMounted(async () => {
  await handleContact(route.params.id as string);
})
</script>