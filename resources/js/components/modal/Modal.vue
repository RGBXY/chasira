<template>
  <div
    :class="active ? 'opacity-100 visible' : 'opacity-0 invisible'"
    @click="close"
    class="fixed top-0 left-0 bg-black backdrop-blur-sm bg-opacity-40 transition-all duration-500 z-50 w-full h-screen flex justify-center items-center"
  >
    <div
      class="w-[500px] bg-white rounded-lg border-2 shadow-lg py-5 px-6"
      @keydown.esc="active = false"
      tabindex="0"
      ref="modalContent"
    >
      <h1 class="text-xl font-bold mb-2">
        <slot name="header"></slot>
      </h1>

      <p class="text-gray-500 text-sm font-semibold mb-4">
        <slot name="description"></slot>
      </p>

      <div class="flex gap-2 justify-end">
        <slot name="action"></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const modalContent = ref(null);

// Menggunakan defineModel untuk two-way binding
const active = defineModel({ type: Boolean, default: false });

// Fungsi untuk menutup modal
const close = () => {
  active.value = false;
};

// Fungsi menangani tombol ESC untuk menutup modal
const closeOnEscape = (event) => {
  if (event.key === 'Escape') {
    active.value = false;
  }
};

// Tambahkan event listener saat modal terbuka
onMounted(() => {
  window.addEventListener('keydown', closeOnEscape);
});

// Hapus event listener saat modal tidak dipakai
onUnmounted(() => {
  window.removeEventListener('keydown', closeOnEscape);
});
</script>
