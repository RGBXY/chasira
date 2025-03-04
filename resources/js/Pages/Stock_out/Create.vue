<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Stock Out</h1>
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
                            name="Initial Display Stock"
                            type="text"
                            :disabled="true"
                            v-model="productsData.stock"
                            placeholder="Your product current stock"
                        />

                        <TextInput
                            class="flex-1"
                            name="Initial Opname Stock"
                            type="text"
                            :disabled="true"
                            v-model="currentOpnameStock"
                            placeholder="Your product current stock"
                        />
                    </div>

                    <TextInput
                        name="Display Stock"
                        type="number"
                        v-model="form.display_stock"
                        placeholder="Your new stock qty"
                        :message="form.errors.qty"
                    />

                    <TextInput
                        name="Opanme Stock"
                        type="number"
                        v-model="form.opname_stock"
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
import { computed, ref, watch } from "vue";

const barcode = ref("");
const productsData = ref("");
const loading = ref(false);

const debouncedSearch = debounce(() => {
    if (barcode.value === "") {
        productsData.value = "";
    }

    loading.value = true;

    axios
        .post("/stock-out/searchProduct", {
            barcode: barcode.value,
        })
        .then((response) => {
            if (response.data.success && response.data.data.length > 0) {
                productsData.value = response.data.data[0];
                console.log(productsData.value);
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
    display_stock: "",
    opname_stock: "",
    detail: "",
});

const currentOpnameStock = computed(
    () => productsData.value.stock_opname?.qty ?? ""
);

const submit = () => {
    form.post("/stock-out");
};

watch(
    () => productsData.value,
    (newVal) => {
        form.product_id = newVal?.id || "";
    }
);
</script>
