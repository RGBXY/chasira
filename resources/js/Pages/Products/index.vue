<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center w-full">
            <div class="px-10 py-8 w-full border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Products</h1>

                    <div class="flex gap-3 justify-between">
                        <div
                            class="w-[300px] h-12 border flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Search Category..."
                                class="h-full outline-none w-full"
                            />
                            <button
                                @click="handleSearch()"
                                class="flex items-center justify-center rounded-full"
                            >
                                <PhMagnifyingGlass class="text-lg" />
                            </button>
                        </div>
                        <div class="h-12 rounded-md bg-white px-2 border">
                            <select
                                name=""
                                id=""
                                v-model="searchCat"
                                @change="categoryChange"
                                class="h-full w-full bg-transparent rounded-lg border-none outline-none"
                            >
                                <option value="" selected disabled>
                                    Category
                                </option>
                                <option
                                    v-for="category in categories"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <Link
                            href="/products/create"
                            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
                        >
                            <PhPlus weight="bold" />
                            <p>Add Product</p>
                        </Link>
                    </div>
                </div>

                <div class="w-full">    
                    <DataTable
                        v-if="props.products.data.length > 0"
                        :data="props.products.data"
                        :header="headerConfig"
                    >
                        <template v-slot:image="{ row: i }">
                            <img
                                class="w-10"
                                :src="'storage/' + i.image"
                                alt=""
                            />
                        </template>
                        <template v-slot:category="{ row: i }">
                            <p>{{ i.category.name }}</p>
                        </template>
                        <template v-slot:buy_price="{ row: i }">
                            <p>{{ formatPrice(i.buy_price) }}</p>
                        </template>
                        <template v-slot:sell_price="{ row: i }">
                            <p>{{ formatPrice(i.sell_price) }}</p>
                        </template>
                        <template v-slot:actions="{ row: i }">
                            <div class="flex items-start gap-2">
                                <Link
                                    :href="`/products/${i.id}/edit`"
                                    class="border-blue-300 border-2 px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-blue-50 font-semibold text-blue-500 text-sm rounded-md"
                                >
                                    <PhPencilLine />
                                    <p>Edit</p>
                                </Link>
                                <button
                                    @click="method.modalDeleteFnc(i.id)"
                                    class="border-red-300 border-2 px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-red-50 font-semibold text-red-500 text-sm rounded-md"
                                >
                                    <PhTrash />
                                    <p>Delete</p>
                                </button>
                            </div>
                        </template>
                    </DataTable>

                    <NoData
                        v-else
                        header="No Products Found"
                        sub="Get started by creating your first product. You can add details, pricing, and stock product."
                        button="Add Product"
                        link="/products/create"
                    />

                    <Pagination :pagination="props.products" />
                </div>
            </div>
        </div>

        <ModalDelete>
            <template #header> Are you absolutly sure? </template>
            <template #description>
                Are you sure you want to delete this product? Once deleted, this
                action cannot be undone and the product will be permanently
                removed.</template
            >
            <template #action>
                <PrimButtonModal
                    @click="method.modalDeleteFnc()"
                    text="Cancel"
                    class="border-2"
                />
                <PrimButtonModal
                    @click="destroy(method.deleteId)"
                    text="Delete"
                    class="bg-red-500 text-white"
                />
            </template>
        </ModalDelete>
    </Layout>
</template>

<script setup>
import {
    PhMagnifyingGlass,
    PhPencilLine,
    PhPlus,
    PhTrash,
} from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import formatPrice from "../../../core/helper/formatPrice";
import { Link, router } from "@inertiajs/vue3";
import { useMethodStore } from "../../stores/method";
import PrimButtonModal from "../../components/ui/primButtonModal.vue";
import { ref } from "vue";
import ModalDelete from "../../components/modal/ModalDelete.vue";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";
import DataTable from "../../components/layouts/DataTable.vue";

const method = useMethodStore();

const headerConfig = [
    { key: "barcode", label: "Barcode" },
    { key: "name", label: "Name" },
    { key: "category", label: "Category" },
    { key: "buy_price", label: "Buy Price" },
    { key: "sell_price", label: "Sell Price" },
    { key: "stock", label: "stock" },
    { key: "image", label: "image" },
];

const search = ref("" || new URL(document.location).searchParams.get("search"));
const searchCat = ref(
    new URL(document.location).searchParams.get("category_id") || ""
);

//define method search
const handleSearch = () => {
    router.get("/products", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

const categoryChange = () => {
    router.get("/products", {
        //send params "q" with value from state "search"
        category_id: searchCat.value,
    });
};

const props = defineProps({
    products: Object,
    categories: Object,
});

const destroy = (id) => {
    router.delete(`/products/${id}`);
    method.modalDeleteFnc();
};
</script>

<style lang="scss" scoped></style>
