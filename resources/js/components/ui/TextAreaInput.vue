<template>
    <div class="flex flex-col mb-2">
        <label for="name" class="mb-1 text-[13px]">{{ name }}</label>
        <textarea
            :rows="rows"
            :class="message ? 'border-red-500' : 'border-gray-300'"
            class="outline-none border text-sm focus-within:border-violet-500 border-gray-300 px-3 pt-2 rounded-lg placeholder:text-sm placeholder:text-gray-400"
            id="name"
            v-model="model"
            :placeholder="placeholder"
        >
        </textarea>
        <small v-if="message" class="text-red-500">{{ message }}</small>
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
    rows: {
        type: String,
        default: "4",
    },
    message: String,
});
</script>
