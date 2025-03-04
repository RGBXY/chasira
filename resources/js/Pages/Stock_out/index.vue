<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center w-full">
            <div class="px-10 py-8 w-full border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Stock Out</h1>

                    <div class="flex gap-3 justify-between">
                        <div
                            class="w-[300px] h-12 border flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Search Stock Out..."
                                class="h-full outline-none w-full"
                            />
                            <button
                                @click="handleSearch()"
                                class="flex items-center justify-center rounded-full"
                            >
                                <PhMagnifyingGlass class="text-lg" />
                            </button>
                        </div>

                        <form
                            @submit.prevent="filter"
                            class="flex gap-3 justify-between"
                        >
                            <div
                                class="w-[300px] h-12 border flex items-center pe-3 gap-3 bg-white rounded-md overflow-hidden"
                            >
                                <label
                                    for="start-date"
                                    class="text-sm bg-violet-400 h-full px-4 text-white flex items-center"
                                >
                                    Starts
                                </label>
                                <input
                                    type="date"
                                    id="start-date"
                                    required
                                    v-model="start_date"
                                    placeholder="Search Category..."
                                    class="h-full outline-none w-full"
                                />
                            </div>
                            <div
                                class="w-[300px] h-12 border flex items-center pe-3 gap-3 bg-white rounded-md overflow-hidden"
                            >
                                <label
                                    for="end-date"
                                    class="text-sm bg-violet-400 h-full px-4 text-white flex items-center"
                                >
                                    End
                                </label>
                                <input
                                    id="end-date"
                                    type="date"
                                    required
                                    v-model="end_date"
                                    placeholder="Search Category..."
                                    class="h-full outline-none w-full"
                                />
                            </div>

                            <button
                                type="submit"
                                class="flex items-center h-12 justify-center px-4 gap-2 bg-violet-400 text-white rounded-md text-sm"
                            >
                                <PhFunnel weight="bold" />
                                Filter
                            </button>
                        </form>

                        <Link
                            v-if="props.hasStockIn"
                            href="/stock-out/create"
                            class="text-white border rounded-lg flex items-center justify-center gap-2 px-4"
                            :class="
                                props.hasStockIn
                                    ? 'bg-violet-400'
                                    : 'bg-violet-200'
                            "
                        >
                            <PhPlus weight="bold" />
                            <p>Add Stock</p>
                        </Link>

                        <button
                            v-else
                            class="text-white border rounded-lg flex items-center justify-center gap-2 px-4"
                            :class="
                                props.hasStockIn
                                    ? 'bg-violet-400'
                                    : 'bg-violet-200'
                            "
                        >
                            <PhPlus weight="bold" />
                            <p>Add Stock</p>
                        </button>
                    </div>
                </div>

                <div class="w-full">
                    <DataTable
                        v-if="props.StockOut.data.length > 0"
                        :data="props.StockOut.data"
                        :header="headerConfig"
                    >
                        <template v-slot:barcode="{ row: i }">
                            <p>{{ i.product.barcode }}</p>
                        </template>
                        <template v-slot:product="{ row: i }">
                            <p>{{ i.product.name }}</p>
                        </template>
                        <template v-slot:date="{ row: i }">
                            <p>{{ formatDate(i.created_at) }}</p>
                        </template>
                        <template v-slot:actions="{ row: i }">
                            <div class="flex items-start gap-2">
                                <button
                                    @click="modalButtonFnc(i.id)"
                                    class="border-gray-300 border px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-primary-bg font-semibold text-gray-500 text-sm rounded-md"
                                >
                                    <PhEye weight="bold" class="text-base" />
                                    <p>Detail</p>
                                </button>
                                <button
                                    @click="method.modalDeleteFnc(i.id)"
                                    class="border-red-300 border px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-red-50 font-semibold text-red-500 text-sm rounded-md"
                                >
                                    <PhTrash />
                                    <p>Delete</p>
                                </button>
                            </div>
                        </template>
                    </DataTable>

                    <NoData
                        v-else
                        header="No Data Stock in Found"
                        sub="Get started by creating your first stock. You can add details, pricing, and stock product."
                        button="Add Product"
                        link="/products/create"
                    />

                    <ModalSalesDetail>
                        <div class="">
                            <div class="p-5 flex flex-col gap-4">
                                <ContentDetail
                                    title="Barcode"
                                    :value="stockOutDetail?.product?.barcode"
                                />
                                <ContentDetail
                                    title="Product"
                                    :value="stockOutDetail?.product?.name"
                                />
                                <ContentDetail
                                    title="Display Stock"
                                    :value="stockOutDetail?.display_stock"
                                />
                                <ContentDetail
                                    title="Opname Stock"
                                    :value="stockOutDetail?.opname_stock"
                                />
                                <ContentDetail
                                    title="Opname Stock"
                                    :value="
                                        formatDate(stockOutDetail?.created_at)
                                    "
                                />
                                <ContentDetail
                                    title="Detail"
                                    :value="stockOutDetail?.detail"
                                />
                            </div>
                        </div>
                    </ModalSalesDetail>

                    <Pagination :pagination="props.StockOut" />
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
    PhEye,
    PhFunnel,
    PhMagnifyingGlass,
    PhPlus,
    PhTrash,
} from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import { Link, router } from "@inertiajs/vue3";
import { useMethodStore } from "../../stores/method";
import PrimButtonModal from "../../components/ui/primButtonModal.vue";
import { ref } from "vue";
import ModalDelete from "../../components/modal/ModalDelete.vue";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";
import DataTable from "../../components/layouts/DataTable.vue";
import formatDate from "../../../core/helper/formatDate";
import ModalSalesDetail from "../../components/modal/ModalSalesDetail.vue";
import ContentDetail from "../../components/ui/ContentDetail.vue";

const method = useMethodStore();
const stockOutDetail = ref(null);

const headerConfig = [
    { key: "barcode", label: "Barcode" },
    { key: "product", label: "Product" },
    { key: "display_stock", label: "Display Stock" },
    { key: "opname_stock", label: "Opname Stock" },
    { key: "date", label: "Date" },
];

const search = ref("" || new URL(document.location).searchParams.get("search"));

const start_date = ref(
    "" || new URL(document.location).searchParams.get("start_date")
);

const end_date = ref(
    "" || new URL(document.location).searchParams.get("end_date")
);

const filter = () => {
    router.get("/stock-out/filter", {
        start_date: start_date.value,
        end_date: end_date.value,
    });
};

//define method search
const handleSearch = () => {
    router.get("/stock-out", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

const props = defineProps({
    StockOut: Object,
    hasStockIn: Boolean,
});

const modalButtonFnc = (id) => {
    method.modalDetailFnc();
    const StockOut = props.StockOut.data;
    const stockOutFiltered = StockOut.find((data) => data.id === id);
    stockOutDetail.value = stockOutFiltered;
};

const destroy = (id) => {
    router.delete(`/stock-out/${id}`);
    method.modalDeleteFnc();
};
</script>
