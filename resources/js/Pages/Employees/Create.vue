<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold mb-1">Add Employee</h1>
                </div>

                <form
                    @submit.prevent="submit()"
                    class="w-full flex flex-col gap-3"
                >
                    <TextInput
                        name="Name"
                        type="text"
                        v-model="form.name"
                        placeholder="Your employee name"
                        :message="form.errors.name"
                    />

                    <TextInput
                        name="Email"
                        type="email"
                        v-model="form.email"
                        placeholder="Your employee email"
                        :message="form.errors.email"
                    />

                    <TextInput
                        name="Password"
                        type="password"
                        v-model="form.password"
                        placeholder="Your employee password"
                        :message="form.errors.password"
                    />

                    <div class="mb-2">
                        <label for="" class="mb-1 text-[13px]">Outlets</label>
                        <div
                            :class="
                                form.errors.asigned_outlet
                                    ? 'border-red-500'
                                    : 'border-gray-300'
                            "
                            class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
                        >
                            <select
                                v-model="form.asigned_outlet"
                                name=""
                                id=""
                                class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
                            >
                                <option value="" disabled selected>
                                    Outlets
                                </option>
                                <option
                                    v-for="outlet in outlets"
                                    :value="outlet.name"
                                >
                                    {{ outlet.name }}
                                </option>
                            </select>
                        </div>
                        <small
                            v-if="form.errors.asigned_outlet"
                            class="text-red-500"
                            >{{ form.errors.asigned_outlet }}</small
                        >
                    </div>

                    <div class="mb-2">
                        <label for="" class="mb-1 text-[13px]">Roles</label>
                        <div
                            :class="
                                form.errors.role
                                    ? 'border-red-500'
                                    : 'border-gray-300'
                            "
                            class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
                        >
                            <select
                                v-model="form.role"
                                name=""
                                id=""
                                class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
                            >
                                <option value="" disabled selected>
                                    Roles
                                </option>
                                <option
                                    v-for="role in roles"
                                    :value="role.name"
                                >
                                    {{ role.name }}
                                </option>
                            </select>
                        </div>
                        <small v-if="form.errors.role" class="text-red-500">{{
                            form.errors.role
                        }}</small>
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
                </form>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../Layouts/Layout.vue";
import TextInput from "../../components/ui/TextInput.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    asigned_outlet: "",
    role: "",
    status: "active",
});

const props = defineProps({
    outlets: Object,
    roles: Object,
});

const submit = () => {
    form.post("/employees");
};
</script>

<style lang="scss" scoped></style>
