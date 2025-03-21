<template>
  <div class="py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-7 flex items-center justify-between pb-4">
        <h1 class="text-3xl font-bold mb-1">Roles</h1>

        <div class="flex gap-3 justify-between">
          <div
            class="w-[250px] border h-10 flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" />
            <input
              type="text"
              v-model="name"
              @keydown="searchByName"
              placeholder="Search Roles..."
              class="h-full outline-none w-full placeholder:text-sm"
            />
          </div>

          <Link
            href="/roles/create"
            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon icon="ph:plus-bold" :ssr="true" />
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
              <th class="text-start p-3 w-[65%]">Permissions</th>
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
                    class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-blue-400 text-sm rounded-md"
                  >
                    <p>Edit</p>
                  </Link>
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
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import { Link, router } from '@inertiajs/vue3';
import { useMethodStore } from '../../stores/method';
import { ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import NoData from '../../components/card/NoData.vue';
import { Icon } from '@iconify/vue/dist/iconify.js';

defineOptions({
  layout: Layout,
});

const method = useMethodStore();

const props = defineProps({
  roles: Object,
});

const destroy = (id) => {
  router.delete(`/roles/${id}`);
  method.modalDeleteFnc();
};
</script>

<style lang="scss" scoped></style>
