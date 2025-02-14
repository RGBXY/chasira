<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Roles</h1>

                    <div class="flex gap-3 justify-between">
                        <div
                            class="w-[300px] h-12 border flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Search Roles..."
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
                            href="/roles/create"
                            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
                        >
                            <PhPlus weight="bold" />
                            <p>Add Roles</p>
                        </Link>
                    </div>
                </div>

                <div class="w-full">
                    <table
                        v-if="props.roles.data.length > 0"
                        class="table-fixed w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Name</th>
                                <th class="text-start p-3 w-[65%]">
                                    Permissions
                                </th>
                                <th class="text-start p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="role in roles.data">
                                <td class="border-2 p-3 border-gray-200">
                                    <p>{{ role.name }}</p>
                                </td>
                                <td class="border-2 p-3 gap-3 border-gray-200">
                                    <div class="flex gap-2 flex-wrap">
                                        <div
                                            v-for="permission in role.permissions"
                                            class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                                        >
                                            <p>{{ permission.name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div class="flex items-start gap-2">
                                        <Link
                                            :href="`/roles/${role.id}/edit`"
                                            class="border-blue-300 border-2 px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-blue-50 font-semibold text-blue-500 text-sm rounded-md"
                                        >
                                            <PhPencilLine />
                                            <p>Edit</p>
                                        </Link>
                                        <button
                                            @click="
                                                method.modalDeleteFnc(role.id)
                                            "
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
                        header="No Data Roles Found"
                        sub="Get started by creating your first role. Define permissions and responsibilities to manage your team effectively."
                        button="Add Data Roles"
                        link="/roles/create"
                    />

                    <Pagination :pagination="props.roles" />
                </div>
            </div>
        </div>

        <ModalDelete>
            <template #header> Are you absolutly sure? </template>
            <template #description>
                Are you sure you want to delete this Role? Once deleted, this
                action cannot be undone and the role will be permanently
                removed.</template
            >
            <template #action>
                <PrimButtonModal
                    @click="method.modalDeleteFnc()"
                    text="Cancel"
                    class="border-2"
                />
                <PrimButtonModal
                    @click="destroy(method.deleteId)"
                    text="Delete"
                    class="bg-red-500 text-white"
                />
            </template>
        </ModalDelete>
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

const search = ref("" || new URL(document.location).searchParams.get("search"));

const handleSearch = () => {
    router.get("/roles", {
        //send params "q" with value from state "search"
        search: search.value,
    });
};

const props = defineProps({
    roles: Object,
});

const destroy = (id) => {
    router.delete(`/roles/${id}`);
    method.modalDeleteFnc();
};
</script>

<style lang="scss" scoped></style>
