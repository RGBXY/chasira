<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Products</h1>
                </div>

                <form
                    @submit.prevent="submit"
                    class="w-full flex flex-col gap-3"
                >
                    <div class="mb-2">
                        <label for="" class="mb-1 text-[13px]">Category</label>
                        <div
                            :class="form.errors.category_id ? 'border-red-500' : 'border-gray-300'"
                            class="h-10 rounded-lg bg-white px-2 border-[1.5px] "
                        >
                            <select
                                v-model="form.category_id"
                                name=""
                                id=""
                                class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
                            >
                                <option value="" disabled selected>
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
                        <small
                            v-if="form.errors.category_id"
                            class="text-red-500"
                            >{{ form.errors.category_id }}</small
                        >
                    </div>

                    <TextInput
                        name="Product Name"
                        type="text"
                        v-model="form.name"
                        placeholder="Your product name"
                        :message="form.errors.name"
                    />

                    <TextInput
                        name="Buy Price"
                        type="number"
                        v-model="form.buy_price"
                        placeholder="Product buy price"
                        :message="form.errors.buy_price"
                    />

                    <TextInput
                        name="Sell Price"
                        type="number"
                        v-model="form.sell_price"
                        placeholder="Product sell price"
                        :message="form.errors.sell_price"
                    />

                    <TextInput
                        name="Product Stock"
                        type="number"
                        v-model="form.stock"
                        placeholder="Product stock"
                        :message="form.errors.stock"
                    />

                    <TextAreaInput
                        v-model="form.description"
                        name="Product Description"
                        placeholder="Your Product Descriptuon"
                        :message="form.errors.description"
                    />

                    <div class="mb-5">
                        <p for="" class="mb-1.5 text-[13px]">Image</p>
                        <label
                            class="h-32 flex flex-col items-center justify-center border-gray-400 rounded-lg border-2 border-dashed"
                        >
                            <input type="file" class="" @input="change" />
                        </label>
                        <small v-if="form.errors.image" class="text-red-500">{{
                            form.errors.image
                        }}</small>
                    </div>

                    <div class="flex justify-end gap-3">
                        <Link
                            href="/products"
                            class="h-10 px-3 flex items-center bg-violet-100 rounded-lg font-semibold text-violet-400"
                        >
                            Back
                        </Link>
                        <button
                            type="submit"
                            class="h-10 px-3 bg-violet-400 rounded-lg font-semibold text-white"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../Layouts/Layout.vue";
import TextInput from "../../components/ui/TextInput.vue";
import TextAreaInput from "../../components/ui/TextAreaInput.vue";
import { Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    category_id: "",
    buy_price: null,
    sell_price: null,
    stock: null,
    description: "",
    image: null,
});

const change = (e) => {
    form.image = e.target.files[0];
};

const submit = () => {
    form.post("/products");
    console.log(form.category_id);
};

const props = defineProps({
    categories: Object,
});
</script>

<style lang="scss" scoped></style>
