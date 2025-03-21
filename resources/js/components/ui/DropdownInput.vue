<template>
  <div class="relative w-full">
    <label :for="label" class="mb-1 text-sm">{{ label }}</label>
    <input
      :class="[message ? 'border-red-500' : 'border-gray-300 ']"
      class="outline-none border text-sm transition-all w-full duration-200 focus-within:border-violet-400 focus-within:shadow focus-within:shadow-violet-300 h-10 px-3 rounded-lg placeholder:text-sm"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      @focus="isFocused = true"
      @blur="handleBlur"
      @keydown="handleKeydown"
      autocomplete="off"
      :placeholder="placeholder"
    />

    <small v-if="message" class="text-red-500">{{ message }}</small>

    <div
      v-if="isFocused"
      v-loading="loading"
      class="absolute top-16 p-2 no-scrollbar bg-white overflow-y-auto border shadow-md rounded-md max-h-32 w-full z-10"
    >
      <button
        v-if="items.length > 0"
        :key="item.id"
        type="button"
        @click="selectItem(item)"
        class="w-full text-start hover:bg-gray-200 px-2 flex gap-1 flex-col py-1 rounded-md"
        v-for="item in items"
      >
        <slot name="item" :item="item">
          {{ item.name }}
        </slot>
      </button>

      <p v-else class="flex h-14 items-center justify-center">
        {{ emptyMessage }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  label: {
    type: String,
    default: '',
  },
  items: {
    type: Array,
    default: () => [],
  },
  loading: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
  },
  emptyMessage: {
    type: String,
    default: 'No Data Found',
  },
  blurDelay: {
    type: Number,
    default: 300,
  },
  message: String,
});

const emit = defineEmits(['update:modelValue', 'keydown', 'select']);

const isFocused = ref(false);

const handleBlur = () => {
  setTimeout(() => {
    isFocused.value = false;
  }, props.blurDelay);
};

const handleKeydown = (event) => {
  emit('keydown', event);
};

const selectItem = (item) => {
  emit('select', item);
};
</script>
