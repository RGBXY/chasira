<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Products</h1>
                </div>

                <div class="w-full flex flex-col gap-3">
                    <TextInput
                        name="Product Name"
                        type="text"
                        placeholder="Your product name"
                    />

                    <TextInput
                        name="Product Price"
                        type="number"
                        placeholder="Your product price"
                    />

                    <div class="mb-2">
                        <label for="" class="mb-1 text-[13px]">Category</label>
                        <div class="h-10 rounded-md bg-white px-2 border">
                            <select
                                name=""
                                id=""
                                class="h-full w-full bg-transparent text-sm border-2 border-gray-300 rounded-lg border-none outline-none"
                            >
                                <option value="" disabled selected>
                                    Category
                                </option>
                                <option value="">Food</option>
                                <option value="">Drink</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <p for="" class="mb-1.5 text-[13px]">Image</p>
                        <label
                            class="h-32 flex items-center justify-center border-gray-400 rounded-lg border-2 border-dashed"
                        >
                            <input type="file" />
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            class="h-10 px-3 bg-violet-100 rounded-lg font-semibold text-violet-400"
                        >
                            Back
                        </button>
                        <button
                            class="h-10 px-3 bg-violet-400 rounded-lg font-semibold text-white"
                        >
                            Submit
                        </button>
                    </div>
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
import TextInput from "../../components/ui/TextInput.vue";
import products from "../../../core/data/product";
import formatPrice from "../../../core/helper/formatPrice";
</script>

<style lang="scss" scoped></style>
