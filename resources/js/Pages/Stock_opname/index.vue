<template>
  <div class="py-8 px-7 flex items-center justify-center w-full">
    <div class="py-8 px-10 w-full border bg-white rounded-lg">
      <div class="mb-7 flex items-center lg:justify-between flex-wrap pb-4">
        <h1 class="text-3xl font-bold lg:mb-1 mb-3">Stock Opname</h1>

        <div class="flex flex-wrap gap-3 lg:justify-between">
          <div
            class="w-[250px] border h-10 flex items-center px-3 gap-3 bg-white rounded-md overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" />
            <input
              type="text"
              v-model="name"
              @keydown="searchStockOpnameName"
              placeholder="Search Stock Opname..."
              class="h-full outline-none w-full placeholder:text-sm"
            />
          </div>

          <div class="">
            <el-date-picker
              v-model="date"
              type="daterange"
              range-separator="To"
              @change="filterDate()"
              start-placeholder="Start date"
              end-placeholder="End date"
              size="large"
            />
          </div>

          <Link
            href="/stock-opname/create"
            class="text-white border rounded-lg h-10 bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon icon="ph:plus-bold" :ssr="true" />
            <p>Add Data</p>
          </Link>
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="stockOpname.data.length > 0"
          :data="stockOpname.data"
          :header="headerConfig"
        >
          <template v-slot:product="{ row: i }">
            <p>{{ i.product.name }}</p>
          </template>
          <template v-slot:date="{ row: i }">
            <p>{{ formatDate(i.created_at) }}</p>
          </template>
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-2">
              <button
                @click="modalButtonFnc(i.id)"
                class="border-gray-300 border px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-primary-bg font-semibold text-gray-500 text-sm rounded-md"
              >
                <p>Detail</p>
              </button>
            </div>
          </template>
        </DataTable>

        <el-empty v-else :image-size="200" />

        <SideModal
          v-model="detailModal"
          title="Stock Detail"
          subTitle="Your stock detail"
        >
          <div class="p-5 flex flex-col gap-4">
            <ContentDetail
              v-for="data in stockDetail"
              :title="data.title"
              :value="data.data"
            />
          </div>
        </SideModal>

        <Pagination :pagination="stockOpname" />
      </div>
    </div>
  </div>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import { computed, ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import DataTable from '../../components/layouts/DataTable.vue';
import formatDate from '../../../core/helper/formatDate';
import ContentDetail from '../../components/ui/ContentDetail.vue';
import SideModal from '../../components/modal/SideModal.vue';
import { Icon } from '@iconify/vue/dist/iconify.js';
import { debounce } from 'lodash';
import { Link, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

// Layout
defineOptions({ layout: Layout });

// Props
const props = defineProps({
  stockOpname: Object,
});

// Config Table
const headerConfig = [
  { key: 'product', label: 'Product' },
  { key: 'actual_stock', label: 'Actual Stock' },
  { key: 'system_stock', label: 'System Stock' },
  { key: 'discrepancy_stock', label: 'Discrepancy Stock' },
  { key: 'date', label: 'Date' },
];

// Config Data Detail
const stockDetail = computed(() => [
  {
    title: 'Product',
    data: stockOpnameDetail?.value?.product?.name,
  },
  {
    title: 'Actual Stock',
    data: stockOpnameDetail?.value?.actual_stock,
  },
  {
    title: 'System Stock',
    data: stockOpnameDetail?.value?.system_stock,
  },
  {
    title: 'Discrepancy Stock',
    data: stockOpnameDetail?.value?.discrepancy_stock,
  },
  {
    title: 'Created At',
    data: formatDate(stockOpnameDetail?.value?.created_at),
  },
  {
    title: 'Detail',
    data: stockOpnameDetail?.value?.description,
  },
]);

// State API
const stockOpnameDetail = ref(null);
const name = ref(new URL(document.location).searchParams.get('name') || '');
const date = ref([
  new URL(document.location).searchParams.get('start_date') || '',
  new URL(document.location).searchParams.get('end_date') || '',
]);
const stockOpnameData = ref(props.stockOpname);
const detailModal = ref(false);

// Function Search Stock Opname  (API)
const searchStockOpnameName = debounce(() => {
  router.get('/stock-opname/searchByName', {
    name: name.value,
  });
}, 500);

// Filter Stock in data (API)
const filterDate = () => {
  router.get('/stock-opname/filterDate', {
    start_date: dayjs(date.value[0]).format('YYYY-MM-DD'),
    end_date: dayjs(date.value[1]).format('YYYY-MM-DD'),
  });
};

// Function Modal
const modalButtonFnc = (id) => {
  detailModal.value = true;

  const stockOpname = props.stockOpname.data;

  const stockOpnameFiltered = stockOpname.find((data) => data.id === id);

  stockOpnameDetail.value = stockOpnameFiltered;
};
</script>
