<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-7 flex items-center justify-between pb-4">
        <h1 class="text-3xl font-bold mb-1">Employees</h1>

        <div class="flex gap-3 justify-between">
          <div
            class="w-[250px] border h-10 flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" />
            <input
              type="text"
              v-model="name"
              @keydown="searchEmployeeName"
              placeholder="Search Employees..."
              class="h-full outline-none w-full placeholder:text-sm"
            />
          </div>

          <Link
            href="/employees/create"
            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon icon="ph:plus-bold" :ssr="true" />
            <p>Add Data</p>
          </Link>
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="employeesData.length > 0"
          :data="employeesData"
          :header="headerConfig"
        >
          <template v-slot:roles="{ row: i }">
            <div
              class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
            >
              <p>
                {{ i.roles[0].name }}
              </p>
            </div>
          </template>
          <template v-slot:status="{ row: i }">
            <div
              :class="
                i.status === 'active'
                  ? 'bg-blue-100 text-blue-500'
                  : 'bg-red-100 text-red-500'
              "
              class="px-2.5 py-1.5 uppercase font-bold inline-block text-sm rounded-md"
            >
              <p>{{ i.status }}</p>
            </div>
          </template>
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-2">
              <button
                @click=""
                :class="
                  i.status === 'Deactive' ? ' text-blue-400' : ' text-red-400'
                "
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-blue-400 text-sm rounded-md"
              >
                <p>{{ i.status === 'active' ? 'Deactive' : 'Active' }}</p>
              </button>
              <Link
                :href="`/products/${i.id}/edit`"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-blue-400 text-sm rounded-md"
              >
                <p>Edit</p>
              </Link>
              <button
                @click="method.modalFnc(i.id)"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-red-400 text-sm rounded-md"
              >
                <p>Delete</p>
              </button>
            </div>
          </template>
        </DataTable>

        <el-empty v-else :image-size="200" />

        <Pagination :pagination="employeesData" />
      </div>
    </div>

    <!-- Modal -->
    <Modal v-model="modalAlert">
      <template #header>Are you absolutely sure?</template>
      <template #description>
        {{
          status === 'inactive'
            ? 'Are you sure you want to activate this employee?'
            : 'Are you sure you want to deactivate this employee?'
        }}
      </template>
      <template #action>
        <PrimaryButton
          @click="method.modalDeleteFnc()"
          class="bg-gray-200"
          text="Close"
        />
        <PrimaryButton
          v-if="status === 'inactive'"
          @click="activate(method.deleteId)"
          class="bg-blue-200 text-blue-600"
          text="Activate"
        />
        <PrimaryButton
          v-else-if="status === 'active'"
          @click="deactivate(method.deleteId)"
          class="bg-red-200 text-red-600"
          text="Deactivate"
        />
      </template>
    </Modal>
    <!-- Modal -->
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useMethodStore } from '../../stores/method';
import { ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import Modal from '../../components/modal/Modal.vue';
import PrimaryButton from '../../components/ui/PrimaryButton.vue';
import { Icon } from '@iconify/vue/dist/iconify.js';
import DataTable from '../../components/layouts/DataTable.vue';
import Layout from '../../Layouts/Layout.vue';
import { debounce } from 'lodash';

// Layout
defineOptions({
  layout: Layout,
});

// Config Table
const headerConfig = [
  { key: 'name', label: 'Name' },
  { key: 'roles', label: 'Role' },
  { key: 'status', label: 'Status' },
];

// Props
const props = defineProps({
  user: Object,
});

// Define Store
const method = useMethodStore();

// State API
const employeesData = ref(props.user.data);
const status = ref(null);
const name = ref('');
const loading = ref('');

const searchEmployeeName = debounce(() => {
  if (name.value.trim() == '') {
    employeesData.value = props.user.data;
    return;
  }

  loading.value = true;

  axios
    .post('/employees/searchEmployeeName', {
      name: name.value,
    })
    .then((response) => {
      console.log(response);
      if (response.data.success && response.data.data.length > 0) {
        employeesData.value = response.data.data;
      } else {
        employeesData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

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
