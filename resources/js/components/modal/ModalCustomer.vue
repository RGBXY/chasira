<template>
  <div
    :class="
      method.modalCustomerStat ? 'opacity-100 visible' : 'opacity-0 invisible'
    "
    @click="method.modalCustomerClose()"
    class="fixed top-0 left-0 bg-black backdrop-blur-sm bg-opacity-40 transition-all duration-500 z-50 w-full h-screen flex justify-center items-center"
  >
    <div
      @click.stop
      class="md:w-[50%] w-[90%] h-[65%] bg-white border rounded-lg"
    >
      <div class="h-20 border-b flex items-center justify-between px-5">
        <h1 class="text-2xl font-bold">List Customer</h1>
        <button @click="method.modalCustomerClose()" class="p-2">
          <Icon icon="ph:x" :ssr="true" class="text-xl text-slate-700" />
        </button>
      </div>

      <div class="p-5">
        <div
          class="w-full mb-2 h-11 border flex items-center px-3 gap-3 bg-white rounded-lg overflow-hidden"
        >
          <Icon icon="ph:magnifying-glass" :ssr="true" class="text-xl" />
          <input
            type="text"
            v-model="phone"
            @keydown="searchCustomerPhone()"
            placeholder="Search Customers..."
            class="h-full outline-none w-full placeholder:text-sm placeholder:font-medium"
          />
        </div>

        <DataTable
          v-if="customersData.length > 0"
          :data="customersData"
          :header="headerConfig"
        >
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-1">
              <button
                @click="selectCustomer(i.id, i.name)"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-slate-600 text-sm rounded-md"
              >
                <p>Select</p>
              </button>
            </div>
          </template>
        </DataTable>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue/dist/iconify.js';
import { useMethodStore } from '../../stores/method';
import DataTable from '../layouts/DataTable.vue';
import { ref } from 'vue';
import { debounce } from 'lodash';
import { useReceiptStore } from '../../stores/receipt';

const method = useMethodStore();
const receipt = useReceiptStore();

const props = defineProps({
  customers: Array,
});

const customersData = ref(props.customers);
const phone = ref('');
const loading = ref(false);

// Config Table
const headerConfig = [
  { key: 'name', label: 'Name' },
  { key: 'phone', label: 'Phone' },
];

// Function Search Category Data (API)
const searchCustomerPhone = debounce(() => {
  if (phone.value.trim() == '') {
    customersData.value = props.customers;
    return;
  }

  loading.value = true;

  axios
    .post('/customers/searchCustomerByPhone', {
      phone: phone.value,
    })
    .then((response) => {
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

const selectCustomer = (id, name) => {
  receipt.customer = {
    id: id,
    name: name,
  };

  method.modalCustomerClose();
};
</script>
