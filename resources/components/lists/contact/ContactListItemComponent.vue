<template>
  <div 
    class="border w-full rounded-lg border-gray-200 hover:bg-gray-100 transition-all ease-in duration-200 p-2 flex flex-row items-center"
  >
    <article class="flex flex-col flex-1 justify-start text-start">
      <h2 class="font-semibold text-base">{{ name }}</h2>

      <h4 class="text-xs">{{ phoneNumber }}</h4>
    </article>


    <button type="button" @click="emitGoToContact">
      <img
        src="../../../assets/icons/arrow-right.svg"
        alt="dots"
        width="24px"
        class=" hover:cursor-pointer"
      >
    </button>
  </div>
</template>

<script lang="ts">
export default {};
</script>

<script lang="ts" setup>
import { computed, ComputedRef, PropType } from 'vue';
import { ContactEntity } from '../../../entities/contact/ContactEntity';
import * as phoneNumberUtil from "../../../utils/phone";

const props = defineProps({
  contact: {
    type: Object as PropType<ContactEntity>,
    required: true,
  }
})

type Emits = { (e: 'go-to-contact', contact: ContactEntity): void };
const emit: Emits = defineEmits(['go-to-contact']);

const name: ComputedRef<string> = computed(() => props.contact.name);

const phoneNumber: ComputedRef<string> = computed(() => {
  if (!props.contact.phones?.length) return "";

  if (!props.contact.phones[0].phone) return "";

  return phoneNumberUtil.format(props.contact.phones[0].phone);
})

function emitGoToContact(): void {
  emit('go-to-contact', props.contact);
}
</script>
