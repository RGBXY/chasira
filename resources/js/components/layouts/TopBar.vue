<template>
  <div
    class="flex sticky z-30 top-0 h-20 items-center px-7 bg-gray-50 justify-between"
  >
    <div class="flex items-center gap-3">
      <button
        aria-label="Navbar Menu"
        @click="method.sideBarStatFnc()"
        class="bg-white hover:bg-violet-50 border h-12 w-12 flex items-center justify-center rounded-full"
      >
        <Icon icon="ph:list" class="text-2xl text-violet-500" />
      </button>

      <div
        class="bg-white border h-12 py-2 ps-2 pe-6 gap-3 flex items-center justify-center rounded-full"
      >
        <div class="bg-violet-50 p-2 rounded-full">
          <Icon icon="ph:calendar-dots" class="text-lg text-violet-500" />
        </div>

        <h1 class="font-semibold text-sm">{{ formattedDate }}</h1>
      </div>

      <div
        class="bg-white border h-12 py-2 ps-2 pe-6 gap-3 flex items-center justify-center rounded-full"
      >
        <div class="bg-violet-50 p-2 rounded-full">
          <Icon icon="ph:clock" class="text-lg text-violet-500" />
        </div>

        <h1 class="font-semibold text-sm">{{ time }}</h1>
      </div>
    </div>

    <Link
      as="button"
      type="button"
      method="post"
      href="/logout"
      class="bg-white hover:bg-violet-50 transition-all group border h-12 py-2 pe-1.5 ps-5 gap-4 flex items-center justify-center rounded-full"
    >
      <h1 class="font-semibold text-sm text-red-500 group-hover:text-red-400">
        Log Out
      </h1>
      <button
        aria-label="Sign out"
        class="bg-red-500 group-hover:bg-red-400 p-2 text-xl rounded-full text-white"
      >
        <Icon icon="ph:sign-out" :ssr="true" />
      </button>
    </Link>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useMethodStore } from '../../stores/method';
import { Icon } from '@iconify/vue/dist/iconify.js';

const method = useMethodStore();

const time = ref('');

const now = new Date();
const options = {
  weekday: 'short',
  day: '2-digit',
  month: 'short',
  year: 'numeric',
};
const formattedDate = new Intl.DateTimeFormat('en-US', options).format(now);

function updateTime() {
  const now = new Date();
  const options = {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  };
  time.value = new Intl.DateTimeFormat('en-US', options).format(now);
}

// Update setiap detik
setInterval(updateTime, 1000);

onMounted(() => {
  updateTime();
});
</script>

<style lang="scss" scoped></style>
