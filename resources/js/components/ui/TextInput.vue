<template>
  <div class="flex flex-col mb-2">
    <label :for="name" class="mb-1 text-sm">{{ name }}</label>
    <input
      :type="type"
      :disabled="disabled"
      @focus="$emit('focus', $event)"
      @keydown="$emit('keydown', $event)"
      @blur="$emit('blur', $event)"
      :class="[
        message ? 'border-red-500' : 'border-gray-300 ',
        disabled
          ? 'bg-slate-100 placeholder:text-gray-500'
          : 'placeholder:text-gray-400',
      ]"
      class="outline-none border text-sm transition-all duration-200 focus-within:border-violet-400 focus-within:shadow focus-within:shadow-violet-300 h-10 px-3 rounded-lg placeholder:text-sm"
      :id="name"
      v-model="model"
      autocomplete="off"
      :placeholder="placeholder"
    />
    <small v-if="message" class="text-red-500">{{ message }}</small>
  </div>
</template>

<script setup>
const model = defineModel({
  type: null,
  required: true,
});

defineEmits(['update:modelValue', 'focus', 'keydown', 'blur']);

defineProps({
  name: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    default: 'text',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  message: String,
});
</script>
