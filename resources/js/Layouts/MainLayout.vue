<template>
  <div class="w-full min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    <div
      :class="[
        'fixed h-full lg:transition-all lg:duration-300 ',
        method.sideBarStat
          ? 'lg:w-[250px] z-50 lg:bg-gray-50 lg:backdrop-blur-0 bg-black w-full bg-opacity-25 backdrop-blur-sm'
          : 'w-[85px] ',
      ]"
    >
      <SideBar />

      <button
        @click="method.sideBarStatFnc"
        v-if="method.sideBarStat"
        class="fixed right-6 top-6 border-2 lg:hidden rounded-full p-2 bg-white text-black"
      >
        <Icon ssr="true" icon="ph:x" />
      </button>
    </div>
    <!-- Konten utama -->
    <div
      :class="[
        'flex-1 pb-5 transition-all duration-300 w-full',
        method.sideBarStat ? 'lg:ml-[250px]' : 'lg:ml-[85px]',
      ]"
    >
      <TopBar />
      <div class="flex items-center justify-center w-full">
        <slot></slot>
      </div>
    </div>

    <!-- Komponen lain -->
    <ReceiptForm />
    <Toaster />
  </div>
</template>

<script setup>
import { defineAsyncComponent } from 'vue';
import ReceiptForm from '../components/layouts/ReceiptForm.vue';
import SideBar from '../components/layouts/SideBar.vue';
import TopBar from '../components/layouts/TopBar.vue';
import { useMethodStore } from '../stores/method';
import { Icon } from '@iconify/vue/dist/iconify.js';

const Toaster = defineAsyncComponent(() =>
  import('../components/modal/Toaster.vue')
);

const method = useMethodStore();
</script>
