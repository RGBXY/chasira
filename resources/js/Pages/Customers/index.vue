<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-8 py-8 w-full border bg-white rounded-lg">
      <div class="mb-7 flex items-center justify-between pb-4">
        <h1 class="text-3xl font-bold mb-1">Customers</h1>

        <div class="flex gap-3 justify-between">
          <div
            class="w-[250px] h-10 border flex items-center px-3 gap-3 bg-white rounded-lg overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" class="text-xl" />
            <input
              type="text"
              v-model="name"
              @keydown="searchCustomerName()"
              placeholder="Search Customers..."
              class="h-full outline-none w-full placeholder:text-sm placeholder:font-medium"
            />
          </div>

          <Link
            href="/customers/create"
            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon icon="ph:plus-bold" :ssr="true" class="text-xl" />
            <p>Add Data</p>
          </Link>
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="customersData.length > 0"
          :data="customersData"
          :header="headerConfig"
        >
          <template v-slot:products_count="{ row: i }">
            <div
              class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
            >
              {{ i.products_count }}
            </div>
          </template>
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-2">
              <Link
                :href="`/customers/${i.id}/edit`"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-blue-400 text-sm rounded-md"
              >
                <p>Edit</p>
              </Link>
              <button
                @click="modalButtonFnc(i.id)"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-red-400 text-sm rounded-md"
              >
                <p>Delete</p>
              </button>
            </div>
          </template>
        </DataTable>

        <el-empty v-else :image-size="200" />

        <Pagination :pagination="customersData" />
      </div>
    </div>

    <Modal v-model="modalAlert">
      <template #header> Are you absolutly sure? </template>
      <template #description>
        Are you sure you want to delete this category? Once deleted, this action
        cannot be undone and the category will be permanently removed.</template
      >
      <template #action>
        <PrimaryButton
          @click="modalAlert = false"
          text="Cancel"
          class="border border-gray-400 bg-transparent !text-gray-500"
        />
        <PrimaryButton
          @click="destroy(dataId)"
          text="Delete"
          class="bg-red-500 text-white"
        />
      </template>
    </Modal>
  </div>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import { Link, router } from '@inertiajs/vue3';
import { useMethodStore } from '../../stores/method';
import { ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import DataTable from '../../components/layouts/DataTable.vue';
import Modal from '../../components/modal/Modal.vue';
import PrimaryButton from '../../components/ui/PrimaryButton.vue';
import { Icon } from '@iconify/vue/dist/iconify.js';
import { debounce } from 'lodash';

// Layout
defineOptions({
  layout: Layout,
});

// Config Table
const headerConfig = [
  { key: 'name', label: 'Name' },
  { key: 'address', label: 'Address' },
  { key: 'phone', label: 'Phone' },
  { key: 'gender', label: 'Gender' },
];

// Define Store
const method = useMethodStore();

// Props
const props = defineProps({
  customers: Object,
});

// State API
const name = ref('');
const customersData = ref(props.customers.data);
const loading = ref();

// State Modal
const modalAlert = ref(false);
const dataId = ref(0);

// Function Search Customers Data by Name (API)
const searchCustomerName = debounce(() => {
  if (name.value.trim() == '') {
    customersData.value = props.customers.data;
    return;
  }

  loading.value = true;

  axios
    .post('/customers/searchCustomerName', {
      name: name.value,
    })
    .then((response) => {
      console.log(response.data);
      if (response.data.success && response.data.data.length > 0) {
        customersData.value = response.data.data;
      } else {
        customersData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

// Function Modal Alert
const modalButtonFnc = (id) => {
  modalAlert.value = true;
  dataId.value = id;
};

// Function Delete
const destroy = (id) => {
  loading.value = true;

  router.delete(`/customers/${id}`, {
    preserveState: false,
    onSuccess: () => {
      method.modalFnc();
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
