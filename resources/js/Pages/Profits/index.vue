<template>
  <Layout>
    <div class="w-full py-8 px-7 flex items-center justify-center">
      <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
        <div class="mb-7 flex flex-wrap items-center justify-between pb-4">
          <h1 class="text-3xl font-bold lg:mb-1 mb-3">Report Profits</h1>

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
        </div>

        <div class="w-full overflow-x-auto">
          <table
            v-if="profits.data.length > 0"
            class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
          >
            <thead>
              <tr class="">
                <th class="text-start p-3">Date</th>
                <th class="text-start p-3">Invoice</th>
                <th class="text-start p-3">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="profit in profits.data">
                <td class="border-2 p-3 border-gray-200">
                  <div class="px-2.5 py-1.5 uppercase inline-block rounded-md">
                    {{ formatDate(profit.created_at) }}
                  </div>
                </td>
                <td class="border-2 p-3 border-gray-200">
                  <div class="px-2.5 py-1.5 uppercase inline-block rounded-md">
                    {{ profit.transaction.invoice }}
                  </div>
                </td>
                <td class="border-2 p-3 border-gray-200">
                  <div class="px-2.5 py-1.5 uppercase inline-block rounded-md">
                    {{ formatPrice(profit.total) }}
                  </div>
                </td>
              </tr>
              <tr class="bg-gray-100">
                <td colspan="2" class="border-2 p-3 border-gray-200 text-end">
                  <div
                    class="px-2.5 py-1.5 font-semibold text-end uppercase inline-block rounded-md"
                  >
                    Total Sum :
                  </div>
                </td>
                <td class="border-2 p-3 border-gray-200 text-end">
                  <div
                    class="px-2.5 py-1.5 font-semibold uppercase inline-block rounded-md"
                  >
                    {{ formatPrice(total) }}
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <el-empty v-else :image-size="200" />

          <Pagination :pagination="profits" />
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import formatPrice from '../../../core/helper/formatPrice';
import { ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import formatDate from '../../../core/helper/formatDate';
import { router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps({
  profits: Object,
  total: String,
});

const date = ref([
  new URL(document.location).searchParams.get('start_date') || '',
  new URL(document.location).searchParams.get('end_date') || '',
]);

const profitsData = ref(props.profits.data);
const total = ref(props.total);

console.log(props.profits);

const filterDate = () => {
  router.get('/profits/filterDate', {
    start_date: dayjs(date.value[0]).format('YYYY-MM-DD'),
    end_date: dayjs(date.value[1]).format('YYYY-MM-DD'),
  });
};
</script>

<style lang="scss" scoped></style>
