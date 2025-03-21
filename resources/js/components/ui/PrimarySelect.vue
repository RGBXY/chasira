<template>
  <div
    :class="[message ? 'border-red-600' : '']"
    class="min-h-10 rounded-md bg-white px-2 border"
  >
    <select
      :disabled="disabled"
      required
      v-model="model"
      class="h-full w-full bg-transparent rounded-lg border-none outline-none"
    >
      <option value="">All</option>
      <option v-for="data in datas" :value="data.id">
        {{ data.name }}
      </option>
    </select>
  </div>
</template>

<script setup>
const model = defineModel({
  type: null,
  required: true,
});

defineProps({
  datas: {
    type: Array,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  message: String,
});
</script>
