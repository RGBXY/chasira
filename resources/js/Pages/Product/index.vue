<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Products</h1>

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
                                placeholder="Search Product..."
                                class="h-full outline-none w-full"
                            />
                        </div>
                        <div class="h-12 rounded-md bg-white px-2 border">
                            <select
                                name=""
                                id=""
                                class="h-full w-full bg-transparent rounded-lg border-none outline-none"
                            >
                                <option value="" disabled selected>
                                    Category
                                </option>
                                <option value="">Food</option>
                                <option value="">Drink</option>
                            </select>
                        </div>
                        <Link
                            href="/products/add"
                            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
                        >
                            <PhPlus weight="bold" />
                            <p>Add Product</p>
                        </Link>
                    </div>
                </div>

                <div class="w-full">
                    <table
                        class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Item</th>
                                <th class="text-start p-3">Category</th>
                                <th class="text-start p-3">Price</th>
                                <th class="text-start p-3">Image</th>
                                <th class="text-start p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products">
                                <td class="border-2 p-3 border-gray-200">
                                    {{ product.name }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                                    >
                                        {{ product.category }}
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    {{ formatPrice(product.price) }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <img
                                        class="w-14 h-14 object-cover"
                                        :src="product.image"
                                        alt=""
                                    />
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
import products from "../../../core/data/product";
import formatPrice from "../../../core/helper/formatPrice";
import { Link } from "@inertiajs/vue3";
</script>

<style lang="scss" scoped></style>
