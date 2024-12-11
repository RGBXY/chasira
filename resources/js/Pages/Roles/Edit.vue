<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Edit Roles</h1>
                </div>

                <form
                    @submit.prevent="submit"
                    class="w-full flex flex-col gap-3"
                >
                    <TextInput
                        name="Roles Name"
                        type="text"
                        v-model="form.name"
                        placeholder="Your product name"
                        :message="form.errors.name"
                    />

                    <div class="">
                        <p class="mb-1 text-sm">Chose Permission</p>
                        <div
                            :class="
                                form.errors.permissions ? 'border-red-500' : ''
                            "
                            class="flex flex-wrap gap-5 border p-2 rounded-lg"
                        >
                            <div
                                v-for="permission in permissions"
                                class="flex items-center gap-1"
                            >
                                <input
                                    :id="`check-${permission.id}`"
                                    type="checkbox"
                                    :value="permission.name"
                                    v-model="form.permissions"
                                />
                                <label :for="`check-${permission.id}`">{{
                                    permission.name
                                }}</label>
                            </div>
                        </div>
                        <small
                            v-if="form.errors.permissions"
                            class="text-red-500"
                            >{{ form.errors.permissions }}</small
                        >
                    </div>

                    <div class="flex justify-end gap-3">
                        <Link
                            href="/roles"
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
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    role: Object,
    permissions: Object,
});

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions.map((obj) => obj.name),
});

const submit = () => {
    form.put(`/roles/${props.role.id}`);
};
</script>

<style lang="scss" scoped></style>
