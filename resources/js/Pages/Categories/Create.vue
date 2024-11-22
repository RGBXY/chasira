<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <form
                @submit.prevent="submit"
                class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg"
            >
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Category</h1>
                </div>

                <div class="w-full flex flex-col gap-3">
                    <TextInput
                        name="Category Name"
                        type="text"
                        v-model="form.name"
                        placeholder="Your Category name"
                        :message="form.errors.name"
                    />

                    <TextAreaInput
                        name="Description"
                        rows="4"
                        v-model="form.description"
                        placeholder="Your Category Description"
                        :message="form.errors.description"
                    />

                    <div class="flex justify-end gap-3">
                        <Link
                            href="/categories"
                            type="button"
                            class="h-10 px-3 bg-violet-100 rounded-lg flex items-center font-semibold text-violet-400"
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
                            <p v-if="form.processing">Loading...</p>
                            <p v-else>Submit</p>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../Layouts/Layout.vue";
import TextInput from "../../components/ui/TextInput.vue";
import TextAreaInput from "../../components/ui/TextAreaInput.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    description: "",
});

const submit = () => {
    form.post("/categories");
};
</script>

<style lang="scss" scoped></style>
