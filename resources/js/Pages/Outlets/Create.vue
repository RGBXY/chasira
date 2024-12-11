<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Outlet</h1>
                </div>

                <form
                    @submit.prevent="submit()"
                    class="w-full flex flex-col gap-3"
                >
                    <TextInput
                        name="Outlet Name"
                        type="text"
                        v-model="form.name"
                        placeholder="Your outlete name"
                        :message="form.errors.name"
                    />

                    <TextInput
                        name="Address"
                        type="text"
                        v-model="form.address"
                        placeholder="Your outlet address"
                        :message="form.errors.address"
                    />

                    <TextInput
                        name="City"
                        type="text"
                        v-model="form.city"
                        placeholder="Your outlet city"
                        :message="form.errors.city"
                    />

                    <TextInput
                        name="Phone Number"
                        type="number"
                        v-model="form.phone"
                        placeholder="Your outlet phone number"
                        :message="form.errors.phone"
                    />

                    <TextInput
                        name="Email"
                        type="email"
                        v-model="form.email"
                        placeholder="Your outlet email"
                        :message="form.errors.email"
                    />

                    <TextArea
                        name="Description"
                        v-model="form.description"
                        placeholder="Description about your outlet"
                        :message="form.errors.description"
                    />

                    <div class="flex justify-end gap-3">
                        <button
                            class="h-10 px-3 bg-violet-100 rounded-lg font-semibold text-violet-400"
                        >
                            Back
                        </button>
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
import TextArea from "../../components/ui/TextAreaInput.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    address: "",
    city: "",
    phone: "",
    email: "",
    description: "",
    status: "active",
});

const submit = () => {
    form.post("/outlets");
};
</script>

<style lang="scss" scoped></style>
