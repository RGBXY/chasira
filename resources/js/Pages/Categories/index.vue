<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
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
                    <table
                        class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Name</th>
                                <th class="text-start p-3">Total Product</th>
                                <th class="text-start p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="category in categories"
                                :key="category.id"
                            >
                                <td class="border-2 p-3 border-gray-200">
                                    {{ category.name }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                                    >
                                        110 Product
                                    </div>
                                </td>

                                <td class="border-2 p-3 border-gray-200">
                                    <div class="flex items-start gap-2">
                                        <Link
                                            :href="`/categories/${category.id}/edit`"
                                            class="bg-blue-100 px-2.5 py-1.5 font-medium text-blue-500 text-sm rounded-md"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click="
                                                method.modalDeactiveFnc(
                                                    category.id
                                                )
                                            "
                                            class="bg-red-100 px-2.5 py-1.5 font-medium text-red-500 text-sm rounded-md"
                                        >
                                            Delete
                                        </button>
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
import { PhMagnifyingGlass, PhPlus } from "@phosphor-icons/vue";
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
