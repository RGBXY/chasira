<template>
    <div class="flex flex-col mb-2">
        <label for="name" class="mb-1 text-[13px]">{{ name }}</label>
        <input
            :type="type"
            class="outline-none border text-sm focus-within:border-violet-500 border-gray-300 h-10 px-3 rounded-lg placeholder:text-sm placeholder:text-gray-400 "
            id="name"
            v-model="model"
            :placeholder="placeholder"
        />
        <small v-if="message">{{ message }}</small>
    </div>
</template>

<script setup>
const model = defineModel({
    type: null,
    required: true,
});

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
        default: "text",
    },
    message: String,
});
</script>
