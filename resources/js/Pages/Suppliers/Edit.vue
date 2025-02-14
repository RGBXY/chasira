<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Edit Supplier</h1>
                </div>

                <form
                    @submit.prevent="submit"
                    class="w-full flex flex-col gap-3"
                >
                    <TextInput
                        name="Supplier Name"
                        type="text"
                        v-model="form.name"
                        placeholder="Your supplier name"
                        :message="form.errors.name"
                    />

                    <TextInput
                        name="Address"
                        type="text"
                        v-model="form.address"
                        placeholder="Your supplier address"
                        :message="form.errors.address"
                    />

                    <TextInput
                        name="Phone"
                        type="number"
                        v-model="form.phone"
                        placeholder="Your supplier phone"
                        :message="form.errors.phone"
                    />

                    <TextAreaInput
                        name="Description"
                        v-model="form.description"
                        placeholder="Your supplier description"
                        :message="form.errors.description"
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

const props = defineProps({
    supplier: Object,
});

const form = useForm({
    name: props.supplier.name,
    address: props.supplier.address,
    phone: props.supplier.phone,
    description: props.supplier.description,
});

const submit = () => {
    form.put(`/suppliers/${props.supplier.id}`);
};
</script>
