<template>
    <MainLayout>
        <div class="px-7 w-full">
            <div
                class="flex items-center w-full gap-2.5 py-3 overflow-x-auto no-scrollbar"
            >
                <CategoryCard :data="props.categories" />
            </div>

            <div class="flex gap-3">
                <div
                    class="w-[70%] mb-3.5 h-14 border flex items-center justify-between bg-white rounded-xl overflow-hidden"
                >
                    <input
                        type="text"
                        placeholder="Search Product By Name..."
                        class="w-[95%] h-full outline-none px-5"
                        v-model="name"
                        ref="searchInput"
                        @keyup="searchProduct()"
                    />

                    <div
                        class="w-[60px] flex items-center border-s bg-gray-100 justify-center rounded-e-xl h-full"
                    >
                        <PhMagnifyingGlass class="text-xl" />
                    </div>
                </div>

                <div
                    class="w-[30%] mb-3.5 h-14 border flex items-center justify-between bg-white rounded-xl overflow-hidden"
                >
                    <input
                        type="text"
                        placeholder="Search Product By Barcode..."
                        class="w-[95%] h-full outline-none px-5"
                        v-model="barcode"
                        ref="searchBarcode"
                        @keyup="searchProduct()"
                    />

                    <div
                        class="w-[60px] flex items-center border-s bg-gray-100 justify-center rounded-e-xl h-full"
                    >
                        <PhBarcode class="text-xl" />
                    </div>
                </div>
            </div>

            <div class="w-full min-h-[70vh] flex flex-col justify-between">
                <div v-if="loading === false">
                    <div
                        v-if="productsData.length > 0"
                        class="mt-2 flex justify-center gap-3 flex-wrap"
                    >
                        <Card :products="productsData" />
                    </div>

                    <NoData
                        v-else
                        header="No Data Products Found"
                        sub="Get started by creating your first product. You can add details,
            pricing, and stock product."
                        button="Add Product"
                        link="/products"
                    />
                </div>
                <div v-else class="flex items-center justify-center w-full">
                    <Loading />
                </div>

                <div>
                    <Pagination
                        v-if="barcode !== ''"
                        :pagination="productsData"
                    />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { PhBarcode, PhMagnifyingGlass } from "@phosphor-icons/vue";
import Card from "../../components/card/Card.vue";
import CategoryCard from "../../components/card/CategoryCard.vue";
import MainLayout from "../../Layouts/MainLayout.vue";
import { onBeforeUnmount, onMounted, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { useReceiptStore } from "../../stores/receipt";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";
import { useMethodStore } from "../../stores/method";
import axios from "axios";
import { onKeyStroke } from "@vueuse/core";
import Loading from "../../components/icon/loading.vue";
import { debounce } from "lodash";

const receiptStore = useReceiptStore();
const page = usePage();
const method = useMethodStore();

const props = defineProps({
    products: Object,
    categories: Object,
    customers: Object,
});

onBeforeUnmount(() => {
    if (page.url !== "/") {
        receiptStore.products = [];
    }
});

const productsData = ref(props.products.data);

const loading = ref(false);

const barcode = ref("");
const name = ref("");

const searchInput = ref(null);
const searchBarcode = ref(null);

onKeyStroke("s", (event) => {
    if (event.altKey) {
        event.preventDefault();
        searchInput.value.focus();
        barcode.value = "";
    }
});

onKeyStroke("b", (event) => {
    if (event.altKey) {
        event.preventDefault();
        searchBarcode.value.focus();
        name.value = "";
    }
});

onKeyStroke("Tab", (event) => {
    if (barcode.value !== "" && productsData.value.length === 1) {
        searchBarcode.value.blur();
        event.preventDefault();

        const newOrder = productsData.value[0];

        const perProduct = receiptStore.products.find(
            (item) => item.id === newOrder.id
        );

        const stock = newOrder.stock;

        if (!perProduct && stock !== 0) {
            newOrder.total = 1;
            receiptStore.products.push(newOrder);
            barcode.value = "";
            searchBarcode.value.focus();
        } else if (perProduct && perProduct.total < perProduct.stock) {
            perProduct.total++;
            barcode.value = "";
            searchBarcode.value.focus();
        }
    }
});

const debouncedSearch = debounce(() => {
    if (barcode.value === "" && name.value === "") {
        productsData.value = props.products.data;
        return;
    }

    loading.value = true;

    axios
        .post("/transactions/searchProduct", {
            barcode: barcode.value,
            name: name.value,
        })
        .then((response) => {
            if (response.data.success && response.data.data.length > 0) {
                productsData.value = response.data.data;
            } else {
                productsData.value = props.products.data;
            }
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            loading.value = false;
        });
}, 500);

const searchProduct = () => {
    debouncedSearch();
};

onMounted(() => {
    router.post("/transactions/destroyCart");
    method.sideBarStat = false;
});

watch(
    () => props.customers,
    (newVal) => {
        if (newVal) {
            receiptStore.customers = newVal;
        }
    }
);
</script>
