<template>
  <ul class="flex flex-col gap-2 w-full">
    <li v-for="item of contacts">
      <contact-list-item 
        :contact="item" 
        @go-to-contact="emitGoToContact" 
      />
    </li>
  </ul>
</template>

<script lang="ts">
export default {};
</script>

<script lang="ts" setup>
import { PropType } from "vue";
import { ContactEntity } from "../../../entities/contact/ContactEntity";
import ContactListItem from "./ContactListItemComponent.vue";

const props = defineProps({
  contacts: {
    type: [] as PropType<Array<ContactEntity>>,
    required: true
  }
})

type Emits = { (e: 'go-to-contact', contact: ContactEntity): void };
const emit: Emits = defineEmits(['go-to-contact']);

function emitGoToContact(contact: ContactEntity): void {
  emit('go-to-contact', contact);
}
</script>
