<script setup>
import Layout from '../../Layouts/Layout.vue';
import formatPrice from '../../../core/helper/formatPrice';
import { computed, ref, watch } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import DataTable from '../../components/layouts/DataTable.vue';
import ContentDetail from '../../components/ui/ContentDetail.vue';
import formatDate from '../../../core/helper/formatDate';
import SideModal from '../../components/modal/SideModal.vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import dayjs from 'dayjs';

defineOptions({
  layout: Layout,
});

const props = defineProps({
  sales: Object,
});

const headerConfig = [
  { key: 'invoice', label: 'Invoice' },
  { key: 'date', label: 'Date' },
  { key: 'customers', label: 'Customers' },
  { key: 'cashier', label: 'Cashier' },
  { key: 'total', label: 'Total' },
  { key: 'discount', label: 'Discount' },
  { key: 'grand_total', label: 'Grand Total' },
];

const headerConfigModal = [
  { key: 'products', label: 'Products' },
  { key: 'price', label: 'Price' },
  { key: 'qty', label: 'Qty' },
];

const saleDetailData = computed(() => [
  {
    title: 'Invoice',
    data: saleDetail.value?.invoice,
  },
  {
    title: 'Date',
    data: formatDate(saleDetail.value.created_at),
  },
  {
    title: 'Cashier',
    data: saleDetail.value.cashier?.name,
  },
  {
    title: 'Customer',
    data: saleDetail.value.customers?.name,
  },
  {
    title: 'Total',
    data: formatPrice(saleDetail.value.total),
  },
  {
    title: 'Cash',
    data: formatPrice(saleDetail.value.cash),
  },
  {
    title: 'Discount',
    data: formatPrice(saleDetail.value.discount),
  },
  {
    title: 'Change',
    data: formatPrice(saleDetail.value.change),
  },
  {
    title: 'Grand Total',
    data: formatPrice(saleDetail.value.grand_total),
  },
]);

const salesData = ref(props.sales);
const saleDetail = ref({});
const transactionDetail = ref([]);
const detailModal = ref(false);
const loading = ref(false);

const filterTransaction = async (id) => {
  loading.value = true;

  try {
    const response = await axios.post('/sales/transaction_detail_filter', {
      id,
    });

    if (response.data.success) {
      saleDetail.value = response.data.data;
      transactionDetail.value = [response.data.transaction_data];
      console.log(transactionDetail.value);
      detailModal.value = true;
    } else {
      transactionDetail.value = [];
      alert('Data transaksi tidak ditemukan.');
    }
  } catch (error) {
    console.error('Gagal memuat data transaksi:', error);
    alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
  } finally {
    loading.value = false;
  }
};

const date = ref([
  new URL(document.location).searchParams.get('start_date') || '',
  new URL(document.location).searchParams.get('end_date') || '',
]);

const filterDate = () => {
  router.get('/sales/filterDate', {
    start_date: dayjs(date.value[0]).format('YYYY-MM-DD'),
    end_date: dayjs(date.value[1]).format('YYYY-MM-DD'),
  });
};
</script>

<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-7 flex flex-wrap items-center justify-between pb-4">
        <h1 class="text-3xl font-bold lg:mb-1 mb-3">Report Sales</h1>

        <div class="">
          <el-date-picker
            v-model="date"
            type="daterange"
            @change="filterDate()"
            range-separator="To"
            start-placeholder="Start date"
            end-placeholder="End date"
            size="large"
          />
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="sales.data.length > 0"
          :data="sales.data"
          :header="headerConfig"
        >
          <template v-slot:date="{ row: i }">
            <div class="uppercase inline-block rounded-md">
              {{ formatDate(i.created_at) }}
            </div>
          </template>
          <template v-slot:customers="{ row: i }">
            <div class="uppercase inline-block rounded-md">
              {{ i.customers?.name }}
            </div>
          </template>
          <template v-slot:cashier="{ row: i }">
            <div class="uppercase inline-block rounded-md">
              {{ i.cashier.name }}
            </div>
          </template>
          <template v-slot:total="{ row: i }">
            <div class="uppercase inline-block rounded-md">
              {{ formatPrice(i.total) }}
            </div>
          </template>
          <template v-slot:discount="{ row: i }">
            <div class="uppercase inline-block rounded-md">
              {{ formatPrice(i.discount) }}
            </div>
          </template>
          <template v-slot:grand_total="{ row: i }">
            <div class="uppercase inline-block rounded-md">
              {{ formatPrice(i.grand_total) }}
            </div>
          </template>

          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-2">
              <button
                :disabled="loading"
                @click="filterTransaction(i.id)"
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
          title="Sale Detail"
          subTitle="Your sale detail"
        >
          <div class="">
            <div class="p-5 flex flex-col gap-4">
              <ContentDetail
                v-for="data in saleDetailData"
                :title="data.title"
                :value="data.data"
              />
            </div>

            <div class="border-t flex flex-col gap-4 p-5">
              <div class="overflow-x-auto">
                <DataTable
                  :header="headerConfigModal"
                  :data="transactionDetail"
                >
                  <template v-slot:products="{ row: i }">
                    <div class="uppercase inline-block rounded-md">
                      {{ i.product.name }}
                    </div>
                  </template>

                  <template v-slot:price="{ row: i }">
                    <div class="uppercase inline-block rounded-md">
                      {{ formatPrice(i.product.buy_price) }}
                    </div>
                  </template>
                </DataTable>
              </div>
            </div>
          </div>
        </SideModal>

        <Pagination :pagination="sales" />
      </div>
    </div>
  </div>
</template>
