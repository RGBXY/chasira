<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Employees</h1>

                    <div class="flex gap-3 justify-between">
                        <div
                            class="w-[300px] h-12 border flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <div
                                class="flex items-center justify-center rounded-full"
                            >
                                <PhMagnifyingGlass class="text-lg" />
                            </div>
                            <input
                                type="text"
                                placeholder="Search Product..."
                                class="h-full outline-none w-full"
                            />
                        </div>
                        <div class="h-12 rounded-md bg-white px-2 border">
                            <select
                                name=""
                                id=""
                                class="h-full w-full bg-transparent rounded-lg border-none outline-none"
                            >
                                <option value="">Active Employees</option>
                                <option value="">Inactive Employees</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <table
                        class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Employee Name</th>
                                <th class="text-start p-3">Role</th>
                                <th class="text-start p-3">Assigned Outlet</th>
                                <th class="text-start p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="employee in employees">
                                <td class="border-2 p-3 border-gray-200">
                                    {{ employee.employe_name }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                                    >
                                        {{ employee.role }}
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    {{ employee.assigned_outlet }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div class="flex items-start gap-2">
                                        <button
                                            @click="method.modalDeactiveFnc"
                                            v-if="employee.status === 'active'"
                                            class="bg-red-100 px-2.5 py-1.5 font-medium text-red-500 text-sm rounded-md"
                                        >
                                            Deactive
                                        </button>
                                        <Link
                                            href="/"
                                            v-else
                                            class="bg-blue-100 px-2.5 py-1.5 font-medium text-blue-500 text-sm rounded-md"
                                        >
                                            Add Employee
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <ModalDeactive>
            <template #header>Are you absolutely sure?</template>
            <template #description
                >Deactivating an employee will remove them from this slot. You
                can assign other employees to this slot after you deactivate
                them. Are you sure you want to continue deactivating?</template
            >
            <template #action>
                <PrimButtonModal
                    @click="method.modalDeactiveFnc"
                    class="bg-gray-200"
                    text="Close"
                />
                <PrimButtonModal
                    class="bg-red-200 text-red-600"
                    text="Deactive"
                />
            </template>
        </ModalDeactive>
        <!-- Modal -->
    </Layout>
</template>

<script setup>
import { PhMagnifyingGlass } from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import employees from "../../../core/data/employees";
import ModalDeactive from "../../components/modal/ModalDeactive.vue";
import PrimButtonModal from "../../components/ui/primButtonModal.vue";
import { useMethodStore } from "../../stores/method";

const method = useMethodStore();
</script>

<style lang="scss" scoped></style>
