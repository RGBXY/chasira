<script setup>
import Layout from '../../Layouts/Layout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import { Icon } from '@iconify/vue/dist/iconify.js';
import { debounce } from 'lodash';
import DataTable from '../../components/layouts/DataTable.vue';

defineOptions({
  layout: Layout,
});

const props = defineProps({
  roles: Object,
});

const headerConfig = [
  { key: 'name', label: 'Name' },
  { key: 'permissions', label: 'Permissions' },
];

const name = ref('');
const rolesData = ref(props.roles.data);

console.log(props.roles.data);

const searchRoles = debounce(() => {
  if (name.value.trim() == '') {
    rolesData.value = props.stockIn.data;
    return;
  }

  axios
    .post('/roles/searchRoles', {
      name: name.value,
    })
    .then((response) => {
      if (response.data.success && response.data.data.length > 0) {
        rolesData.value = response.data.data;
        console.log(rolesData.value);
      } else {
        rolesData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
    });
}, 500);

// const destroy = (id) => {
//   router.delete(`/roles/${id}`);
//   method.modalDeleteFnc();
// };
</script>

<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
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
              @keydown="searchRoles"
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
        <DataTable
          v-if="rolesData.length > 0"
          :data="rolesData"
          :header="headerConfig"
        >
          <template v-slot:permissions="{ row: i }">
            <div class="flex gap-2 flex-wrap">
              <div
                v-for="permissions in i.permissions"
                class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
              >
                <p>
                  {{ permissions.name }}
                </p>
              </div>
            </div>
          </template>
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-2">
              <Link
                :href="`/roles/${i.id}/edit`"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-blue-400 text-sm rounded-md"
              >
                <p>Edit</p>
              </Link>
            </div>
          </template>
        </DataTable>

        <el-empty v-else :image-size="200" />

        <Pagination :pagination="rolesData" />
      </div>
    </div>
  </div>
</template>
