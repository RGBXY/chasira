<template>
  <div class="w-full min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    <div
      :class="[
        'fixed h-full transition-all duration-300',
        method.sideBarStat ? 'w-[250px]' : 'w-[85px]',
      ]"
    >
      <SideBar />
    </div>

    <!-- Konten utama -->
    <div
      :class="[
        'flex-1 pb-5 transition-all duration-300',
        method.sideBarStat ? 'ml-[250px]' : 'ml-[85px]',
      ]"
    >
      <TopBar />
      <div class="flex items-center justify-center w-full">
        <slot></slot>
      </div>
    </div>

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

const Toaster = defineAsyncComponent(
  () => import('../components/modal/Toaster.vue')
);

const method = useMethodStore();
</script>
