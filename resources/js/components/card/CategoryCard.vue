<template>
    <button
        @click="handleSearchCat('')"
        class="w-[155px] flex flex-col justify-center px-4 py-3 bg-white rounded-xl flex-shrink-0 border border-gray-200"
    >
        <h1 class="font-semibold">All</h1>
        <p class="text-sm text-gray-400">Product</p>
    </button>
    <button
        v-for="category in data"
        :key="category.id"
        @click="handleSearchCat(category.id)"
        class="w-[155px] flex flex-col justify-center px-4 py-3 bg-white rounded-xl flex-shrink-0 border border-gray-200"
    >
        <h1 class="font-semibold">{{ category.name }}</h1>
        <p class="text-sm text-gray-400">
            {{ category.products_count }} Product
        </p>
    </button>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    data: Object,
});

const searchCat = ref(
    "" || new URL(document.location).searchParams.get("category_id")
);

const handleSearchCat = (id) => {
    searchCat.value = id;

    router.get("/", {
        //send params "q" with value from state "search"
        category_id: searchCat.value,
    });
};
</script>
