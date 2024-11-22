<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Category</h1>
                </div>

                <div class="w-full flex flex-col gap-3">
                    <TextInput
                        name="Category Name"
                        type="text"
                        placeholder="Your Category name"
                    />

                    <TextAreaInput
                        name="Description"
                        rows="4"
                        placeholder="Your Category Description"
                    />

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
import Layout from "../../Layouts/Layout.vue";
import TextInput from "../../components/ui/TextInput.vue";
import TextAreaInput from "../../components/ui/TextAreaInput.vue";


</script>

<style lang="scss" scoped></style>
