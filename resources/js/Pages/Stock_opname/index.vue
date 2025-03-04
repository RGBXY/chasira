<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center w-full">
            <div class="px-10 py-8 w-full border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Stock Opname</h1>

                    <div class="flex gap-3 justify-between">
                        <div
                            class="w-[300px] h-12 border flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Search Stock in..."
                                class="h-full outline-none w-full"
                            />
                            <button
                                @click="handleSearch()"
                                class="flex items-center justify-center rounded-full"
                            >
                                <PhMagnifyingGlass class="text-lg" />
                            </button>
                        </div>

                        <Link
                            href="/stock-out/create"
                            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
                        >
                            <PhPlus weight="bold" />
                            <p>Add Stock Out</p>
                        </Link>
                    </div>
                </div>

                <div class="w-full">
                    <DataTable
                        v-if="props.stock_opname.data.length > 0"
                        :data="props.stock_opname.data"
                        :header="headerConfig"
                    >
                        <template v-slot:qty="{ row: i }">
                            <div class="flex items-start gap-2">
                                <p v-if="i.stock_opname == null">0</p>
                                <p v-else>{{ i.stock_opname?.qty }}</p>
                            </div>
                        </template>
                        <template v-slot:date="{ row: i }">
                            <div class="flex items-start gap-2">
                                <p v-if="i.stock_opname == null">null</p>
                                <p v-else>
                                    {{ formatDate(i.stock_opname?.updated_at) }}
                                </p>
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

                    <Pagination :pagination="props.stock_opname" />
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
import { Link, router } from "@inertiajs/vue3";
import { useMethodStore } from "../../stores/method";
import PrimButtonModal from "../../components/ui/primButtonModal.vue";
import { ref } from "vue";
import ModalDelete from "../../components/modal/ModalDelete.vue";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";
import DataTable from "../../components/layouts/DataTable.vue";
import formatDate from "../../../core/helper/formatDate";

const method = useMethodStore();

const headerConfig = [
    { key: "name", label: "Product" },
    { key: "qty", label: "qty" },
    { key: "date", label: "Updated At" },
];

const search = ref("" || new URL(document.location).searchParams.get("search"));

//define method search
const handleSearch = () => {
    router.get("/suppliers", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

const props = defineProps({
    stock_opname: Object,
});

console.log(props.stock_opname);

const destroy = (id) => {
    router.delete(`/stock-in/${id}`);
    method.modalDeleteFnc();
};
</script>
