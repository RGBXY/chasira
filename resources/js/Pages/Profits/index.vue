<template>
  <Layout>
    <div class="w-full py-8 px-7 flex items-center justify-center">
      <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
        <div class="mb-7 flex items-center justify-between pb-4">
          <h1 class="text-3xl font-bold mb-1">Report Profits</h1>

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

        <div class="w-full">
          <table
            v-if="profitsData.length > 0"
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
              <tr v-for="profit in profitsData">
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

          <Pagination :pagination="profitsData" />
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
import { debounce } from 'lodash';

const props = defineProps({
  profits: Object,
  total: Number,
});

const date = ref('');
const profitsData = ref(props.profits.data);
const total = ref(props.total);

const filterDate = debounce(() => {
  if (date.value == '') {
    profitsData.value = props.stockIn.data;
    total.value = props.total.data;
    return;
  }

  axios
    .post('/profits/filterDate', {
      start_date: date.value[0],
      end_date: date.value[1],
    })
    .then((response) => {
      console.log(response.data.data);

      if (response.data.success && response.data.data.data.length > 0) {
        profitsData.value = response.data.data.data;
        total.value = response.data.total;

        console.log(profitsData.value);
        console.log(total.value);
      } else {
        profitsData.value = [];
        total.value = null;
      }
    })
    .catch((error) => {
      console.error(error);
    });
}, 500);
</script>

<style lang="scss" scoped></style>
