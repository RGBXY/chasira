<template>
    <button
        v-for="category in categories"
        class="w-[155px] flex flex-col justify-center px-4 py-3 bg-white rounded-xl flex-shrink-0 border border-gray-200"
    >
        <h1 class="font-semibold">{{ category.name }}</h1>
        <p class="text-sm text-gray-400">110 items</p>
    </button>
</template>

<script setup>
import categories from "../../../core/data/categories";
</script>
