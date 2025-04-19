<template>
  <div
    :class="method.toasterStat ? 'right-5' : '-right-full'"
    class="fixed bottom-5 z-50 bg-gradient-to-b from-violet-200 via-violet-50 to-primary-bg transition-all delay-200 duration-300 ease-in-out border-2 rounded-xl shadow-lg w-[350px] max-h-[400px] min-h-[100px] overflow-y-auto"
  >
    <div class="w-full h-full relative">
      <div class="flex items-start justify-between gap-4 h-full p-4 w-full">
        <div
          class="h-12 w-12 shadow-lg flex items-center flex-shrink-0 justify-center rounded-full bg-opacity-25 bg-white border border-primary-bg backdrop-blur-md"
        >
          <Icon
            icon="ph:info-fill"
            :ssr="true"
            class="text-3xl flex-shrink-0 text-primary"
          />
        </div>

        <div class="text-gray-950 flex-1">
          <h1 class="font-bold text-lg mb-0.5">Information</h1>
          <p>
            {{ message || method.toasterMessage }}
          </p>
        </div>

        <button
          @click="method.toasterFncClose"
          class="text-lg flex-shrink-0 text-slate-600 rounded-full"
        >
          <Icon icon="ph:x" />
        </button>
      </div>

      <!-- <div
                class="w-full h-0.5 bg-gradient-to-b from-gray-100 to bg-gray-500 bar absolute bottom-0"
            ></div> -->
    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { useMethodStore } from '../../stores/method';
import { onMounted, ref, watch, watchEffect } from 'vue';
import { Icon } from '@iconify/vue/dist/iconify.js';

const method = useMethodStore();
const page = usePage();
const message = ref(page.props.flash.message);

watch(
  () => message.value,
  (newValue) => {
    if (newValue !== null) {
      method.toasterFnc();
    } else {
      method.toasterFncClose();
    }
  },
  { immediate: true }
);

onMounted(() => {
  watchEffect(() => {
    if (method.toasterStat === true) {
      const intervalId = setInterval(() => {
        method.toasterFncClose();

        if (!method.toasterStat) {
          clearInterval(intervalId);
        }
      }, 5000);
    } else {
      method.toasterFncClose();
    }
  });
});
</script>

<style>
.bar {
  animation: toaster linear;
  animation-duration: 5s;
  animation-fill-mode: forwards;
}

@keyframes toaster {
  from {
    width: 0px;
  }
  to {
    width: 100%;
  }
}
</style>
