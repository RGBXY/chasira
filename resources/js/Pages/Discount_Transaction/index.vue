<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-7 flex items-center justify-between pb-4">
        <h1 class="text-3xl font-bold mb-1">Discount Transactions</h1>

        <div class="flex gap-3 justify-between">
          <div
            class="w-[250px] border h-10 flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" />
            <input
              type="text"
              v-model="name"
              @keydown="searchDiscountName"
              placeholder="Search Discount..."
              class="h-full outline-none w-full placeholder:text-sm"
            />
          </div>

          <Link
            href="/discount-transactions/create"
            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon icon="ph:plus-bold" :ssr="true" />
            <p>Add Data</p>
          </Link>
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="discountData.length > 0"
          :data="discountData"
          :header="headerConfig"
        >
          <template v-slot:discount="{ row: i }">
            <p>{{ i.discount }}%</p>
          </template>
          <template v-slot:minimal_transaction="{ row: i }">
            <p>{{ formatPrice(i.minimal_transaction) }}</p>
          </template>
          <template v-slot:customer_only="{ row: i }">
            <p>{{ i.customer_only == 1 ? 'Customer' : 'All' }}</p>
          </template>
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-2">
              <Link
                :href="`/discount-transactions/${i.id}/edit`"
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

        <Pagination :pagination="discountData" />
      </div>
    </div>

    <!-- Modal -->
    <Modal v-model="modalAlert">
      <template #header>Are you absolutely sure?</template>
      <template #description> Are you sure want to delete this data?</template>
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
import formatPrice from '../../../core/helper/formatPrice';

// Layout
defineOptions({
  layout: Layout,
});

// Config Table
const headerConfig = [
  { key: 'name', label: 'Name' },
  { key: 'code', label: 'Code' },
  { key: 'start_date', label: 'Start Date' },
  { key: 'end_date', label: 'End Date' },
  { key: 'discount', label: 'Discount' },
  { key: 'minimal_transaction', label: 'Minimal' },
  { key: 'customer_only', label: 'Target' },
];

// Props
const props = defineProps({
  discount: Object,
});

// State Modal
const modalAlert = ref(false);
const dataId = ref(0);

// State API
const discountData = ref(props.discount.data);
const name = ref('');
const loading = ref('');

// Function Search Employee By Name (API)
const searchDiscountName = debounce(() => {
  if (name.value.trim() == '') {
    discountData.value = props.discount.data;
    return;
  }

  loading.value = true;

  axios
    .post('/discount-transactions/searchDiscountName', {
      name: name.value,
    })
    .then((response) => {
      if (response.data.success && response.data.data.length > 0) {
        discountData.value = response.data.data;
      } else {
        discountData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

// Function Modal Delete
const modalDeleteFnc = (id) => {
  modalAlert.value = true;
  dataId.value = id;
};

// Function Delete
const destroy = (id) => {
  loading.value = true;

  router.delete(`/discount-transactions/${id}`, {
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
</script>

<style lang="scss" scoped></style>
