<template>
  <div class="w-full flex flex-col gap-2 items-center justify-center h-[500px]">
    <el-empty :image-size="200" />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import PrimButtonModal from '../ui/PrimaryButton.vue';

const props = defineProps({
  header: String,
  sub: String,
  button: String,
  link: String,
});
</script>
