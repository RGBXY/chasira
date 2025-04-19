<template>
  <div class="flex items-center justify-center py-8 px-9 w-full h-full">
    <div class="h-full w-full max-w-7xl">
      <div class="mb-8 flex flex-col justify-center">
        <h1 class="text-2xl font-bold mb-1">Dashboard</h1>
        <p class="text-gray-600 font-medium">
          Check the sales, value and bounce rate by country.
        </p>
      </div>

      <div
        v-if="hasAnyPermission(['dashboard.index'])"
        class="flex items-center lg::flex-row flex-wrap lg:gap-5 gap-5 w-full h-full mb-7"
      >
        <div
          class="p-5 rounded-xl lg:basis-0 basis-[calc(50%-10px)] flex-1 min-h-[120px] bg-white border border-gray-300"
        >
          <div
            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
          >
            <Icon icon="ph:coins" :ssr="true" class="text-3xl" />
          </div>

          <div>
            <h1 class="text-2xl font-bold mb-1">
              {{ formatPrice(props.sale) }}
            </h1>
            <p class="text-gray-500">Total Sales Today</p>
          </div>
        </div>
        <div
          class="p-5 rounded-xl flex-1 lg:basis-0 basis-[calc(50%-10px)] min-h-[120px] bg-white border border-gray-300"
        >
          <div
            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
          >
            <Icon icon="ph:hand-coins" :ssr="true" class="text-3xl" />
          </div>

          <div>
            <h1 class="text-2xl font-bold mb-1">
              {{ formatPrice(props.profit) }}
            </h1>
            <p class="text-gray-500">Total Profits Today</p>
          </div>
        </div>
        <div
          class="p-5 rounded-xl flex-1 min-h-[120px] bg-white border border-gray-300"
        >
          <div
            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
          >
            <Icon icon="ph:users" :ssr="true" class="text-3xl" />
          </div>

          <div>
            <h1 class="text-2xl font-bold">{{ props.total_employees }}</h1>
            <p class="text-gray-500 mb-1">Total Employees</p>
          </div>
        </div>
        <div
          class="p-5 rounded-xl flex-1 min-h-[120px] bg-white border border-gray-300"
        >
          <div
            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
          >
            <Icon icon="ph:users" :ssr="true" class="text-3xl" />
          </div>

          <div>
            <h1 class="text-2xl font-bold">{{ props.total_customers }}</h1>
            <p class="text-gray-500 mb-1">Total Customers</p>
          </div>
        </div>
      </div>

      <div class="w-full flex flex-col md:flex-row gap-5">
        <div class="flex-1 bg-white rounded-xl border-gray-300 border p-5">
          <h1 class="text-xl font-semibold">Total Sales Week</h1>
          <p class="text-gray-500">Total Sales This Last Week</p>
          <LineChart
            :chartData="sales"
            :options="options"
            class="h-[200px] mt-5"
          />
        </div>
        <div
          v-if="hasAnyPermission(['dashboard.index'])"
          class="flex-1 bg-white rounded-xl border border-gray-300 p-5"
        >
          <h1 class="text-xl font-semibold">Total Profits Week</h1>
          <p class="text-gray-500">Total Profits This Last Week</p>
          <LineChart
            :chartData="profits"
            :options="options"
            class="h-[200px] mt-5"
          />
        </div>
      </div>

      <div class="py-8 flex flex-col md:flex-row gap-5 w-full justify-center">
        <div
          class="px-10 py-8 flex-1 border border-gray-300 bg-white rounded-lg"
        >
          <div class="flex flex-col pb-4 border-b-2 mb-0.5">
            <h1 class="text-2xl font-bold mb-1">Best Seller Products</h1>
            <p class="text-gray-500">Product best seller</p>
          </div>

          <div class="w-full">
            <table
              class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
            >
              <thead>
                <tr class="">
                  <th class="text-start p-3">No</th>
                  <th class="text-start p-3">Name</th>
                  <th class="text-start p-3">Total Sell</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(product, index) in best_products">
                  <td class="border-2 p-3 border-gray-200">
                    <p>{{ index + 1 }}</p>
                  </td>
                  <td class="border-2 p-3 border-gray-200">
                    <p>{{ product.name }}</p>
                  </td>
                  <td class="border-2 p-3 border-gray-200">
                    <p>{{ product.total }}</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div
          v-if="hasAnyPermission(['dashboard.index'])"
          class="px-10 py-8 flex-1 border border-gray-300 bg-white rounded-lg"
        >
          <div class="mb-0.5 flex flex-col pb-4 border-b-2">
            <h1 class="text-2xl font-bold mb-1">Must Restock Product</h1>
            <p class="text-gray-500">Product that need to restock</p>
          </div>

          <div class="w-full">
            <table
              class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
            >
              <thead>
                <tr class="">
                  <th class="text-start p-3">No</th>
                  <th class="text-start p-3">Name</th>
                  <th class="text-start p-3">Stock</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(product, index) in stock_product">
                  <td class="border-2 p-3 border-gray-200">
                    <p>{{ index + 1 }}</p>
                  </td>
                  <td class="border-2 p-3 border-gray-200">
                    <p>{{ product.name }}</p>
                  </td>
                  <td class="border-2 p-3 border-gray-200">
                    <p>{{ product.stock }}</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import { BarChart, LineChart } from 'vue-chart-3';
import { Chart, registerables } from 'chart.js';
import formatPrice from '../../../core/helper/formatPrice';
import { ref } from 'vue';
import { Icon } from '@iconify/vue/dist/iconify.js';

defineOptions({
  layout: Layout,
});

const props = defineProps({
  sale: Number,
  profit: Number,
  total_employees: Number,
  total_customers: Number,

  sales_date_week: Array,
  grand_total_week: Array,

  profits_date_week: Array,
  profits_total_week: Array,

  best_products: Array,

  stock_product: Array,
});

Chart.register(...registerables);

const options = ref({
  responsive: true,
  plugins: {
    legend: {
      display: false,
    },
    title: {
      display: false,
    },
  },
  scales: {
    y: {
      ticks: {
        callback: function (value) {
          return formatPrice(value);
        },
      },
    },
  },
  beginZero: true,
});

const sales = {
  labels: props.sales_date_week,
  datasets: [
    {
      label: ['Sales'],
      data: props.grand_total_week,
      backgroundColor: ['#7c3aed'],
    },
  ],
};

const profits = {
  labels: props.profits_date_week,
  datasets: [
    {
      label: ['Sales'],
      data: props.profits_total_week,
      backgroundColor: ['#7c3aed'],
    },
  ],
};

// const props = defineProps({
//   total_sales: String,
//   profit: String,
//   total_employees: Number,
//   total_outlets: Number,

//   sales_date_week: Array,
//   grand_total_week: Array,

//   profits_date_week: Array,
//   profits_total_week: Array,

//   best_outlets: Array,
//   best_outlets_transaction: Array,

//   best_employees: Array,
//   transaction_count_employees: Array,

//   best_product: Array,

//   stock_product: Array,
// });

// console.log(props.best_employees);

// Chart.register(...registerables);

//

// const sales = {
//   labels: props.sales_date_week,
//   datasets: [
//     {
//       label: ['Sales'],
//       data: props.grand_total_week,
//       backgroundColor: ['#7c3aed'],
//     },
//   ],
// };

// const profits = {
//   labels: props.profits_date_week,
//   datasets: [
//     {
//       label: ['Sales'],
//       data: props.profits_total_week,
//       backgroundColor: ['#7c3aed'],
//     },
//   ],
// };

// const outlets = {
//   labels: props.best_outlets,
//   datasets: [
//     {
//       label: ['Transaction'],
//       data: props.best_outlets_transaction,
//       backgroundColor: ['#7c3aed'],
//     },
//   ],
// };

// const employees = {
//   labels: props.best_employees,
//   datasets: [
//     {
//       label: ['Transaction'],
//       data: props.transaction_count_employees,
//       backgroundColor: ['#7c3aed'],
//     },
//   ],
// };
</script>
