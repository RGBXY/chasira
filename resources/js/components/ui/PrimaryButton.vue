<template>
  <button
    :type="type"
    class="min-h-10 px-3 md:px-4 rounded-lg flex flex-shrink-0 items-center gap-2 text-xs md:text-sm text-white font-medium bg-primary"
  >
    <Icon v-if="icon" :icon="icon" :ssr="true" />
    {{ text }}
  </button>
</template>

<script setup>
import { Icon } from '@iconify/vue/dist/iconify.js';

defineProps({ text: String, icon: String, type: String });
</script>
