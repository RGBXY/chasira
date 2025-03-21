<template>
  <button
    :type="type"
    class="md:px-4 rounded-lg flex items-center gap-2 text-xs md:text-sm font-medium border text-[#704ae3] border-[#704ae3]"
  >
    <Icon v-if="icon" :icon="icon" :ssr="true" />
    {{ text }}
  </button>
</template>

<script setup>
import { Icon } from '@iconify/vue/dist/iconify.js';

defineProps({
  text: String,
  icon: String,
  type: String,
});
</script>
