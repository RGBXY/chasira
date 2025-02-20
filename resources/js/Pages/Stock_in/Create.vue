<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Stock in</h1>
                </div>

                <form
                    @submit.prevent="submit"
                    class="w-full flex flex-col gap-3"
                >
                    <TextInput
                        name="Product Barcode"
                        type="text"
                        v-model="barcode"
                        placeholder="Your product barcode "
                        :message="form.errors.product_id"
                        @keydown="debouncedSearch()"
                    />

                    <div class="flex justify-between gap-3">
                        <TextInput
                            class="flex-1"
                            name="Product Name"
                            type="text"
                            :disabled="true"
                            v-model="productsData.name"
                            placeholder="Product Name "
                        />

                        <TextInput
                            class="flex-1"
                            name="Initial Stock"
                            type="text"
                            :disabled="true"
                            v-model="productsData.stock"
                            placeholder="Your product current stock"
                        />
                    </div>

                    <div class="mb-2">
                        <label for="" class="mb-1 text-[13px]">Supplier</label>
                        <div
                            :class="
                                form.errors.supplier_id
                                    ? 'border-red-500'
                                    : 'border-gray-300'
                            "
                            class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
                        >
                            <select
                                v-model="form.supplier_id"
                                name=""
                                id=""
                                class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
                            >
                                <option value="" disabled selected>
                                    Supplier
                                </option>
                                <option
                                    v-for="supplier in props.suppliers"
                                    :value="supplier.id"
                                >
                                    {{ supplier.name }}
                                </option>
                            </select>
                        </div>
                        <small
                            v-if="form.errors.supplier_id"
                            class="text-red-500"
                            >{{ form.errors.supplier_id }}</small
                        >
                    </div>

                    <TextInput
                        name="Qty"
                        type="number"
                        v-model="form.qty"
                        placeholder="Your new stock qty"
                        :message="form.errors.qty"
                    />

                    <TextAreaInput
                        name="Detail"
                        v-model="form.detail"
                        placeholder="Your supplier description"
                        :message="form.errors.detail"
                    />

                    <div class="flex justify-end gap-3">
                        <Link
                            href="/suppliers"
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
import { Link, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash";
import { ref, watch } from "vue";

const props = defineProps({
    products: Object,
    suppliers: Object,
});

const barcode = ref("");
const productsData = ref("");
const loading = ref(false);

const debouncedSearch = debounce(() => {
    if (barcode.value === "") {
        productsData.value = "";
    }

    loading.value = true;

    axios
        .post("/stock-in/searchProduct", {
            barcode: barcode.value,
        })
        .then((response) => {
            if (response.data.success && response.data.data.length > 0) {
                productsData.value = response.data.data[0];
            }
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            loading.value = false;
        });
}, 500);

const form = useForm({
    product_id: "",
    supplier_id: "",
    qty: "",
    detail: "",
});

const submit = () => {
    console.log(form);
};

watch(
    () => productsData.value,
    (newVal) => {
        form.product_id = newVal?.id || "";
    }
);
</script>
