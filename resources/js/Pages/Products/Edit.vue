<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Edit Products</h1>
                </div>

                <form
                    @submit.prevent="update()"
                    class="w-full flex flex-col gap-3"
                >
                    <div class="mb-2">
                        <label for="" class="mb-1 text-[13px]">Category</label>
                        <div
                            :class="
                                form.errors.category_id
                                    ? 'border-red-500'
                                    : 'border-gray-300'
                            "
                            class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
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
                            :disabled="form.processing"
                            :class="
                                form.processing
                                    ? 'cursor-not-allowed bg-violet-300 text-gray-200'
                                    : ''
                            "
                            class="h-10 px-3 bg-violet-400 rounded-lg font-semibold text-white"
                        >
                            {{ form.processing ? "Loading..." : "Submit" }}
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
import { Link, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    categories: Object,
    product: Object,
});

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    buy_price: props.product.buy_price,
    sell_price: props.product.sell_price,
    description: props.product.description,
    stock: props.product.stock,
    image: null,
    barcode: props.product.barcode,
});

const change = (e) => {
    form.image = e.target.files[0];
};

const update = () => {
    //send data to server
    router.post(`/products/${props.product.id}`, {
        //data
        _method: "PUT",
        name: form.name,
        category_id: form.category_id,
        buy_price: form.buy_price,
        sell_price: form.sell_price,
        description: form.description,
        stock: form.stock,
        image: form.image,
        barcode: form.barcode,
    });
};
</script>

<style lang="scss" scoped></style>
