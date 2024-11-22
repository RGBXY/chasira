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
                            <div
                                class="flex items-center justify-center rounded-full"
                            >
                                <PhMagnifyingGlass class="text-lg" />
                            </div>
                            <input
                                type="text"
                                placeholder="Search Category..."
                                class="h-full outline-none w-full"
                            />
                        </div>

                        <Link
                            href="/categories/add"
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
                            <tr v-for="category in categories">
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
                                        <button
                                            class="bg-blue-100 px-2.5 py-1.5 font-medium text-blue-500 text-sm rounded-md"
                                        >
                                            Edit
                                        </button>
                                        <button
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
    </Layout>
</template>

<script setup>
import {
    PhDownloadSimple,
    PhFunnelSimple,
    PhMagnifyingGlass,
    PhPlus,
} from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import categories from "../../../core/data/categories";
import formatPrice from "../../../core/helper/formatPrice";
import { Link } from "@inertiajs/vue3";
</script>

<style lang="scss" scoped></style>
