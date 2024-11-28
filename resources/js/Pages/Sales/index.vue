<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Report Sales</h1>

                    <div class="flex gap-3 justify-between">
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
                                v-model="search1"
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
                                v-model="search"
                                placeholder="Search Category..."
                                class="h-full outline-none w-full"
                            />
                        </div>

                        <button
                            @click="handleSearch()"
                            class="flex items-center h-12 justify-center px-4 gap-2 bg-violet-400 text-white rounded-md text-sm"
                        >
                            <PhFunnel weight="bold" />
                            Filter
                        </button>
                    </div>
                </div>

                <div class="w-full">
                    <table
                        class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Date</th>
                                <th class="text-start p-3">Invoice</th>
                                <th class="text-start p-3">Cashier</th>
                                <th class="text-start p-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                                    >
                                        20-11-2024, 08:50
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="px-2.5 py-1.5 uppercase inline-block rounded-md"
                                    >
                                        TRX-J53U13F5M0
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="px-2.5 py-1.5 inline-block rounded-md"
                                    >
                                        Admin
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="px-2.5 py-1.5 uppercase inline-block rounded-md"
                                    >
                                        Rp.30.000
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <ModalDeactive>
            <template #header> Are you absolutly sure? </template>
            <template #description>
                Are you sure you want to delete this category? Once deleted,
                this action cannot be undone and the category will be
                permanently removed.</template
            >
            <template #action>
                <PrimButtonModal
                    @click="method.modalDeactiveFnc()"
                    text="Cancel"
                    class="border-2"
                />
                <PrimButtonModal
                    @click="destroy(method.deleteId)"
                    text="Delete"
                    class="bg-red-500 text-white"
                />
            </template>
        </ModalDeactive>
    </Layout>
</template>

<script setup>
import { PhFunnel, PhMagnifyingGlass, PhPlus } from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import { Link, router } from "@inertiajs/vue3";
import ModalDeactive from "../../components/modal/ModalDeactive.vue";
import { useMethodStore } from "../../stores/method";
import PrimButtonModal from "../../components/ui/primButtonModal.vue";
import { ref } from "vue";

const method = useMethodStore();

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
    method.modalDeactiveFnc();
};

defineProps({
    categories: Object,
});
</script>

<style lang="scss" scoped></style>
