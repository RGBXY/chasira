<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-8 py-8 w-full border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Categories</h1>

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

                        <Link
                            href="/categories/create"
                            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
                        >
                            <PhPlus weight="bold" />
                            <p>Add Category</p>
                        </Link>
                    </div>
                </div>

                <div class="w-full">
                    <DataTable
                        v-if="props.categories.data.length > 0"
                        :data="props.categories.data"
                        :header="headerConfig"
                    >
                        <template v-slot:products_count="{ row: i }">
                            <div
                                class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                            >
                                {{ i.products_count }}
                            </div>
                        </template>
                        <template v-slot:actions="{ row: i }">
                            <div class="flex items-start gap-2">
                                <Link
                                    :href="`/categories/${i.id}/edit`"
                                    class="border-blue-300 border-2 px-2.5 py-1.5 flex items-center gap-1.5 font-semibold hover:bg-blue-50 text-blue-500 text-sm rounded-md"
                                >
                                    <PhPencil />
                                    <p>Edit</p>
                                </Link>
                                <button
                                    @click="method.modalDeleteFnc(i.id)"
                                    class="border-red-300 border-2 px-2.5 py-1.5 flex items-center gap-1.5 font-semibold hover:bg-red-50 text-red-500 text-sm rounded-md"
                                >
                                    <PhTrash />
                                    <p>Delete</p>
                                </button>
                            </div>
                        </template>
                    </DataTable>

                    <NoData
                        v-else
                        header="No Data Categories Found"
                        sub="Get started by creating category you need. Organize categories to manage products more easily."
                        button="Add Categories"
                        link="/categories/create"
                    />

                    <Pagination :pagination="props.categories" />
                </div>
            </div>
        </div>

        <ModalDelete>
            <template #header> Are you absolutly sure? </template>
            <template #description>
                Are you sure you want to delete this category? Once deleted,
                this action cannot be undone and the category will be
                permanently removed.</template
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
    PhPencil,
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

const method = useMethodStore();

const headerConfig = [
    { key: "name", label: "Name" },
    { key: "products_count", label: "Product Count" },
];

const search = ref("" || new URL(document.location).searchParams.get("search"));

//define method search
const handleSearch = () => {
    router.get("/categories", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

const destroy = (id) => {
    router.delete(`/categories/${id}`);
    method.modalDeleteFnc();
};

const props = defineProps({
    categories: Object,
});
</script>

<style lang="scss" scoped></style>
