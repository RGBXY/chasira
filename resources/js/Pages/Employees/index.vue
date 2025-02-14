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
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Search Employees..."
                                class="h-full outline-none w-full"
                            />
                            <button
                                @click="handleSearch()"
                                class="flex items-center justify-center rounded-full"
                            >
                                <PhMagnifyingGlass class="text-lg" />
                            </button>
                        </div>

                        <Link
                            href="/employees/create"
                            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
                        >
                            <PhPlus weight="bold" />
                            <p>Add Employees</p>
                        </Link>
                    </div>
                </div>

                <div class="w-full">
                    <table
                        v-if="props.user.data.length > 0"
                        class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Name</th>
                                <th class="text-start p-3">Asigned Outlet</th>
                                <th class="text-start p-3">Role</th>
                                <th class="text-start p-3">Status</th>
                                <th class="text-start p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in user.data">
                                <td class="border-2 p-3 border-gray-200">
                                    <p>{{ item.name }}</p>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <p>
                                        {{
                                            item.outlet
                                                ? item.outlet.name
                                                : "null"
                                        }}
                                    </p>
                                </td>
                                <td class="border-2 p-3 gap-3 border-gray-200">
                                    <div
                                        class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                                    >
                                        <p>
                                            {{
                                                item.role
                                                    ? item.role.name
                                                    : "null"
                                            }}
                                        </p>
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        :class="
                                            item.status === 'active'
                                                ? 'bg-blue-100 text-blue-500'
                                                : 'bg-red-100 text-red-500'
                                        "
                                        class="px-2.5 py-1.5 uppercase font-bold inline-block text-sm rounded-md"
                                    >
                                        {{ item.status }}
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div class="flex items-start gap-2">
                                        <button
                                            @click="
                                                statusModal(
                                                    item.status,
                                                    item.id
                                                )
                                            "
                                            :class="
                                                item.status === 'active'
                                                    ? 'border-red-300 text-red-500 hover:bg-red-50'
                                                    : 'border-blue-300 text-blue-500 hover:bg-blue-50'
                                            "
                                            class="px-2.5 py-1.5 min-w-[100px] border-2 font-semibold text-sm rounded-md"
                                        >
                                            {{
                                                item.status === "active"
                                                    ? "Deactivate"
                                                    : "Activate"
                                            }}
                                        </button>
                                        <Link
                                            :href="`/employees/${item.id}/edit`"
                                            class="border-blue-300 border-2 px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-blue-50 font-semibold text-blue-500 text-sm rounded-md"
                                        >
                                            <PhPencilLine />
                                            <p>Edit</p>
                                        </Link>
                                        <button
                                            @click="statusModal(null, item.id)"
                                            class="border-red-300 border-2 px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-red-50 font-semibold text-red-500 text-sm rounded-md"
                                        >
                                            <PhTrash />
                                            <p>Delete</p>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <NoData
                        v-else
                        header="No Data Employees Found"
                        sub="Get started by adding your first employee. Manage your team more efficiently"
                        button="Add Employee"
                        link="/employees/create"
                    />

                    <Pagination :pagination="props.user" />
                </div>
            </div>
        </div>

        <!-- Modal -->
        <ModalDelete>
            <template #header>Are you absolutely sure?</template>
            <template #description>
                {{
                    status === "inactive"
                        ? "Are you sure you want to activate this employee?"
                        : status === "active"
                        ? "Are you sure you want to deactivate this employee?"
                        : "Delete this employee will remove it permanently."
                }}
            </template>
            <template #action>
                <PrimButtonModal
                    @click="method.modalDeleteFnc()"
                    class="bg-gray-200"
                    text="Close"
                />
                <PrimButtonModal
                    v-if="status === 'inactive'"
                    @click="activate(method.deleteId)"
                    class="bg-blue-200 text-blue-600"
                    text="Activate"
                />
                <PrimButtonModal
                    v-else-if="status === 'active'"
                    @click="deactivate(method.deleteId)"
                    class="bg-red-200 text-red-600"
                    text="Deactivate"
                />
                <PrimButtonModal
                    v-else
                    @click="destroy(method.deleteId)"
                    class="bg-red-200 text-red-600"
                    text="Delete"
                />
            </template>
        </ModalDelete>

        <!-- Modal -->
    </Layout>
</template>

<script setup>
import {
    PhMagnifyingGlass,
    PhPencilLine,
    PhPlus,
    PhTrash,
} from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import { Link, router } from "@inertiajs/vue3";
import { useMethodStore } from "../../stores/method";
import PrimButtonModal from "../../components/ui/primButtonModal.vue";
import ModalDelete from "../../components/modal/ModalDelete.vue";
import { ref } from "vue";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";

const method = useMethodStore();
const status = ref(null);

const search = ref("" || new URL(document.location).searchParams.get("search"));

const handleSearch = () => {
    router.get("/employees", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

const props = defineProps({
    user: Object,
});

const statusModal = (data, id) => {
    status.value = data;
    method.modalDeleteFnc(id);
};

const destroy = (id) => {
    router.delete(`/employees/${id}`);
    method.modalDeleteFnc();
};

const activate = (id) => {
    router.put(`/employees/${id}/activate`);
    method.modalDeleteFnc();
};

const deactivate = (id) => {
    router.put(`/employees/${id}/deactivate`);
    method.modalDeleteFnc();
};
</script>

<style lang="scss" scoped></style>
