<template>
    <MainLayout>
        <div class="px-7 mt-1 w-full">
            <div
                class="flex items-center w-full gap-2.5 py-3 overflow-x-auto no-scrollbar"
            >
                <CategoryCard :data="props.categories" />
            </div>

            <div
                class="w-full mb-3.5 h-14 border flex items-center justify-between px-2 bg-white rounded-full overflow-hidden"
            >
                <input
                    type="text"
                    placeholder="Search Product..."
                    class="w-full h-full outline-none px-5"
                    v-model="search"
                />

                <button
                    @click="handleSearch()"
                    class="w-[40px] flex items-center bg-gray-100 justify-center rounded-full h-[40px]"
                >
                    <PhMagnifyingGlass class="text-xl" />
                </button>
            </div>

            <div class="w-full">
                <div
                    class="mt-2 flex items-start justify-center gap-3 flex-wrap"
                >
                    <Card :data="props.products" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { PhMagnifyingGlass } from "@phosphor-icons/vue";
import Card from "../../components/card/Card.vue";
import CategoryCard from "../../components/card/CategoryCard.vue";
import MainLayout from "../../Layouts/MainLayout.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    products: Object,
    categories: Object,
});

const search = ref("" || new URL(document.location).searchParams.get("search"));

const handleSearch = () => {
    router.get("/", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};
</script>
