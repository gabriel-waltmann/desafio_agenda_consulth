<template>
  <button :type="buttonType" :class="`border border-gray-200 text-gray-600 py-1 px-2 rounded-2xl cursor-pointer ${customClass}`">
    <slot></slot>
  </button>
</template>

<script lang="ts">
export default {};
</script>

<script lang="ts" setup>
import { PropType } from 'vue';

const props = defineProps({
  buttonType: {
    type: String as PropType<"button" | "submit" | "reset">,
    required: false,
    default: "button"
  },
  customClass: {
    type: String,
    required: false,
    default: ""
  }
})
</script>