<template>
    <MainLayout>
        <div class="px-7 w-full">
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

            <div class="w-full min-h-[70vh] flex flex-col justify-between">
                <div
                    v-if="props.products.data.length > 0"
                    class="mt-2 flex justify-center gap-3 flex-wrap"
                >
                    <Card :products="props.products" />
                </div>

                <NoData
                    v-else
                    header="No Data Products Found"
                    sub="Get started by creating your first product. You can add details,
            pricing, and stock product."
                    button="Add Product"
                    link="/products"
                />

                <div>
                    <Pagination :pagination="props.products" />
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
import { onBeforeUnmount, onMounted, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { useReceiptStore } from "../../stores/receipt";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";
import { useMethodStore } from "../../stores/method";

const receiptStore = useReceiptStore();
const page = usePage();
const method = useMethodStore();

const props = defineProps({
    products: Object,
    categories: Object,
});

onBeforeUnmount(() => {
    if (page.url !== "/") {
        receiptStore.products = [];
    }
});

const search = ref("" || new URL(document.location).searchParams.get("search"));

const handleSearch = () => {
    router.get("/", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

onMounted(() => {
    router.post("/transactions/destroyCart");
    method.sideBarStat = false;
});
</script>
