<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-7 flex flex-wrap items-center justify-between pb-4">
        <h1 class="text-3xl font-bold lg:mb-1 mb-3">Employees</h1>

        <div class="flex flex-wrap gap-3 justify-between">
          <div
            class="w-[250px] border h-10 flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" />
            <input
              type="text"
              v-model="name"
              @keydown="searchEmployeesName"
              placeholder="Search Employees..."
              class="h-full outline-none w-full placeholder:text-sm"
            />
          </div>

          <Link
            href="/employees/create"
            class="text-white h-10 border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon icon="ph:plus-bold" :ssr="true" />
            <p>Add Data</p>
          </Link>
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="user.data.length > 0"
          :data="user.data"
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
                @click="modalStatusFnc(i.id, i.status)"
                :class="
                  i.status === 'inactive' ? 'text-blue-400 ' : '  text-red-400'
                "
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-sm rounded-md"
              >
                <p>{{ i.status === 'active' ? 'Deactive' : 'Active' }}</p>
              </button>
              <Link
                :href="`/employees/${i.id}/edit`"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-blue-400 text-sm rounded-md"
              >
                <p>Edit</p>
              </Link>
              <button
                @click="modalDeleteFnc(i.id)"
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
      <template #description> Are you sure want to delete this data </template>
      <template #action>
        <PrimaryButton
          @click="modalAlert = false"
          class="border border-gray-400 bg-transparent !text-gray-500"
          text="Close"
        />
        <PrimaryButton
          @click="destroy(dataId)"
          class="bg-red-500 text-white"
          text="Delete"
        />
      </template>
    </Modal>

    <Modal v-model="modalAlert2">
      <template #header>Are you absolutely sure?</template>
      <template #description>
        {{
          statusRef === 'inactive'
            ? 'Are you sure you want to activate this employee?'
            : 'Are you sure you want to deactivate this employee?'
        }}
      </template>
      <template #action>
        <PrimaryButton
          @click="modalAlert2 = false"
          class="border border-gray-400 bg-transparent !text-gray-500"
          text="Close"
        />
        <PrimaryButton
          v-if="statusRef === 'inactive'"
          @click="activate(dataId2)"
          class="!bg-blue-500 text-blue-600"
          text="Activate"
        />
        <PrimaryButton
          v-else-if="statusRef === 'active'"
          @click="deactivate(dataId2)"
          class="bg-red-500 text-red-600"
          text="Deactivate"
        />
      </template>
    </Modal>
    <!-- Modal -->
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
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

// State Modal
const modalAlert = ref(false);
const dataId = ref(0);
const modalAlert2 = ref(false);
const dataId2 = ref(0);
const statusRef = ref(null);

// State API
const employeesData = ref(props.user.data);
const name = ref(new URL(document.location).searchParams.get('name') || '');
const loading = ref('');

// Function Search Employee By Name (API)
const searchEmployeesName = debounce(() => {
  router.get('/employees/searchEmployeesName', {
    name: name.value,
  });
}, 500);

// Function Modal Delete
const modalDeleteFnc = (id) => {
  modalAlert.value = true;
  dataId.value = id;
};

// Function Modal Status
const modalStatusFnc = (id, status) => {
  modalAlert2.value = true;
  dataId2.value = id;
  statusRef.value = status;
};

// Function Delete
const destroy = (id) => {
  loading.value = true;

  router.delete(`/employees/${id}`, {
    preserveState: false,
    onSuccess: () => {
      modalAlert.value = false;
    },
    onError: (errors) => {
      console.error(errors);
    },
    onFinish: () => {
      loading.value = false;
    },
  });
};

// Function Active
const activate = (id) => {
  loading.value = true;

  router.get(`/employee/${id}/activate`, {
    preserveState: false,
    onSuccess: () => {
      modalAlert2.value = false;
    },
    onError: (errors) => {
      console.error(errors);
    },
    onFinish: () => {
      loading.value = false;
    },
  });
};

// Function Deactive
const deactivate = (id) => {
  loading.value = true;

  router.get(`/employee/${id}/deactivate`, {
    preserveState: false,
    onSuccess: () => {
      modalAlert2.value = false;
    },
    onError: (errors) => {
      console.error(errors);
    },
    onFinish: () => {
      loading.value = false;
    },
  });
};
</script>

<style lang="scss" scoped></style>
