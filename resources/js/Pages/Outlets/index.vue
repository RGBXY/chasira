<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Outlets</h1>

                    <div class="flex gap-3 justify-between">
                        <div
                            class="w-[300px] h-12 border flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Search Outlets..."
                                class="h-full outline-none w-full"
                            />
                            <button
                                @click="handleSearch()"
                                class="flex items-center justify-center rounded-full"
                            >
                                <PhMagnifyingGlass class="text-lg" />
                            </button>
                        </div>

                        <div class="h-12 rounded-md bg-white px-2 border">
                            <select
                                name=""
                                id=""
                                class="h-full w-full bg-transparent rounded-lg border-none outline-none"
                                v-model="searchStat"
                                @change="statusChange"
                            >
                                <option value="" selected>All</option>
                                <option value="active">Active Outlets</option>
                                <option value="inactive">
                                    Inactive Outlets
                                </option>
                            </select>
                        </div>

                        <Link
                            href="/outlets/create"
                            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
                        >
                            <PhPlus weight="bold" />
                            <p>Add Outlets</p>
                        </Link>
                    </div>
                </div>

                <div class="w-full">
                    <table
                        class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Outlets Name</th>
                                <th class="text-start p-3">Address</th>
                                <th class="text-start p-3">Phone</th>
                                <th class="text-start p-3">Status</th>
                                <th class="text-start p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="outlet in outlets">
                                <td class="border-2 p-3 border-gray-200">
                                    {{ outlet.name }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    {{ outlet.address }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    {{ outlet.phone }}
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        :class="
                                            outlet.status === 'inactive'
                                                ? 'bg-red-100 text-red-500'
                                                : 'bg-blue-100 text-blue-500 '
                                        "
                                        class="px-2.5 py-1.5 uppercase font-bold inline-block text-sm rounded-md"
                                    >
                                        {{ outlet.status }}
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div class="flex items-start gap-2">
                                        <button
                                            @click="
                                                statusModal(
                                                    outlet.status,
                                                    outlet.id
                                                )
                                            "
                                            :class="
                                                outlet.status === 'active'
                                                    ? 'border-red-300 text-red-500 hover:bg-red-50'
                                                    : 'border-blue-300 text-blue-500 hover:bg-blue-50'
                                            "
                                            class="px-2.5 py-1.5 min-w-[100px] border-2 font-semibold text-sm rounded-md"
                                        >
                                            {{
                                                outlet.status === "active"
                                                    ? "Deactivate"
                                                    : "Activate"
                                            }}
                                        </button>

                                        <Link
                                            :href="`/outlets/${outlet.id}/edit`"
                                            class="px-2.5 py-1.5 font-semibold text-blue-500 hover:bg-blue-50 border-blue-300 border-2 flex items-center gap-1.5 text-sm rounded-md"
                                        >
                                            <PhPencilLine />
                                            <p>Edit</p>
                                        </Link>

                                        <button
                                            @click="
                                                statusModal(null, outlet.id)
                                            "
                                            class="px-2.5 py-1.5 font-semibold border-2 hover:bg-red-50 text-red-500 flex items-center gap-1.5 border-red-300 text-sm rounded-md"
                                        >
                                            <PhTrash />
                                            <p>Delete</p>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <ModalDelete>
            <template #header>Are you absolutely sure?</template>
            <template #description>
                {{
                    status === "inactive"
                        ? "Are you sure you want to activate this outlet?"
                        : status === "active"
                        ? "Are you sure you want to deactivate this outlet?"
                        : "Delete this outlet will remove it permanently."
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
import PrimButtonModal from "../../components/ui/primButtonModal.vue";
import { useMethodStore } from "../../stores/method";
import ModalDelete from "../../components/modal/ModalDelete.vue";
import { ref } from "vue";

const method = useMethodStore();
const status = ref(null);

const props = defineProps({
    outlets: Object,
});

const search = ref(new URL(document.location).searchParams.get("search")) || "";
const searchStat = ref(
    new URL(document.location).searchParams.get("status") || ""
);

//define method search
const handleSearch = () => {
    router.get("/outlets", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

const statusChange = () => {
    router.get("/outlets", {
        //send params "q" with value from state "search"
        status: searchStat.value,
    });
};

const statusModal = (data, id) => {
    status.value = data;
    method.modalDeleteFnc(id);
};

const activate = (id) => {
    router.put(`/outlets/${id}/activate`);
    method.modalDeleteFnc();
};

const deactivate = (id) => {
    router.put(`/outlets/${id}/deactivate`);
    method.modalDeleteFnc();
};

const destroy = (id) => {
    router.delete(`/outlets/${id}`);
    method.modalDeleteFnc();
};  
</script>

<style lang="scss" scoped></style>
