<template>
  <div
    :class="isOpen ? 'opacity-100 visible' : 'opacity-0 invisible'"
    @click="close"
    class="fixed w-full h-screen top-0 left-0 transition-all right-0 p-3 bg-black bg-opacity-15 z-40 flex items-center justify-end"
  >
    <div
      @click.stop
      :class="isOpen ? 'right-2.5' : '-right-1/2'"
      class="w-1/3 h-[98%] overflow-y-auto flex flex-col bg-white rounded-xl fixed transition-all border border-gray-400"
    >
      <div
        class="h-20 flex-shrink-0 flex items-center px-5 sticky top-0 bg-white shadow-sm justify-between border-b"
      >
        <div class="mt-1">
          <h1 class="text-xl font-semibold">{{ title }}</h1>
          <p class="text-sm text-gray-600">{{ subTitle }}</p>
        </div>

        <button
          @click="close"
          class="bg-gray-100 hover:bg-gray-200 p-2.5 rounded-lg"
        >
          <Icon icon="ph:x-bold" class="text-xl text-gray-700" :ssr="true" />
        </button>
      </div>

      <div class="flex-1">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue/dist/iconify.js';

const props = defineProps({
  title: String,
  subTitle: String,
});

// Menggunakan defineModel untuk two-way binding
const isOpen = defineModel({ type: Boolean, default: false });

// Fungsi untuk menutup modal
const close = () => {
  isOpen.value = false;
};
</script>
