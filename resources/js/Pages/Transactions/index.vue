<script setup>
import CategoryCard from '../../components/card/CategoryCard.vue';
import MainLayout from '../../Layouts/MainLayout.vue';
import {
  defineAsyncComponent,
  onBeforeUnmount,
  onMounted,
  ref,
  watch,
} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useReceiptStore } from '../../stores/receipt';
import { useMethodStore } from '../../stores/method';
import axios from 'axios';
import { onKeyStroke } from '@vueuse/core';
import { debounce } from 'lodash';
import { Icon } from '@iconify/vue/dist/iconify.js';

// Layout
defineOptions({ layout: MainLayout });

// Lazy Component
const NoData = defineAsyncComponent(() =>
  import('../../components/card/NoData.vue')
);

const Pagination = defineAsyncComponent(() =>
  import('../../components/ui/Pagination.vue')
);

const Card = defineAsyncComponent(() =>
  import('../../components/card/Card.vue')
);

const Loading = defineAsyncComponent(() =>
  import('../../components/icon/loading.vue')
);

// Pinia Global Store
const receiptStore = useReceiptStore();
const method = useMethodStore();

// Inertia Page
const page = usePage();

// Props
const props = defineProps({
  products: Object,
  categories: Object,
  customers: Object,
});

// State
const productsData = ref(props.products.data);

const loading = ref(false);

const barcode = ref('');
const name = ref('');

const searchInput = ref(null);
const searchBarcode = ref(null);

// Shortcut
onKeyStroke('f', (event) => {
  if (event.altKey) {
    event.preventDefault();
    searchInput.value.focus();
    barcode.value = '';
  }
});

onKeyStroke('b', (event) => {
  if (event.altKey) {
    event.preventDefault();
    searchBarcode.value.focus();
    name.value = '';
  }
});

// Function
const addOrder = (order) => {
  if (order && order.stock > 0) {
    const existingProduct = receiptStore.products.find(
      (p) => p.id === order.id
    );

    if (!existingProduct) {
      receiptStore.products.push({ ...order, total: 1 });
    } else if (
      existingProduct &&
      existingProduct.total < existingProduct.stock
    ) {
      existingProduct.total++;
    }

    barcode.value = '';
    searchBarcode.value.focus();
  } else if (order.stock === 0) {
    method.toasterFnc('Product stock is empty');
    barcode.value = '';
    searchBarcode.value.focus();
  }
};

// Search By Name
const searchByName = debounce(() => {
  if (name.value.trim() == '') {
    productsData.value = props.products.data;
    return;
  }

  loading.value = true;

  axios
    .post('/products/searchProductName', {
      name: name.value,
    })
    .then((response) => {
      if (response.data.success && response.data.data.length > 0) {
        productsData.value = response.data.data;
      } else {
        productsData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

// Search By Barcode
const searchByBarcode = debounce(() => {
  const trimmedBarcode = barcode.value.trim();

  if (trimmedBarcode === '') {
    return;
  }

  axios
    .post('/transactions/searchByBarcode', {
      barcode: trimmedBarcode,
    })
    .then((response) => {
      if (response.data.data !== null) {
        addOrder(response.data.data);
      } else {
        method.toasterFnc("Can't find product");
        barcode.value = '';
        searchBarcode.value.focus();
      }
    })
    .catch((error) => {
      console.error(error);
    });
}, 200);

// const printReceipt = async () => {
//     try {
//         const response = await axios.get("/transactions/printReceipt");
//         alert(response.data.message || response.data.error);
//     } catch (error) {
//         alert(error);
//     }
// };

onMounted(() => {
  method.sideBarStat = false;
});

onBeforeUnmount(() => {
  if (page.url !== '/') {
    receiptStore.products = [];
  }
});

watch(
  () => props.customers,
  (newVal) => {
    if (newVal) {
      receiptStore.customers = newVal;
    }
  }
);
</script>

<template>
  <div class="px-7 w-full">
    <div
      class="flex items-center w-full gap-2.5 py-3 overflow-x-auto no-scrollbar"
    >
      <CategoryCard :data="props.categories" />
    </div>

    <div class="flex gap-3">
      <div
        class="w-[70%] mb-3.5 h-14 border flex items-center justify-between bg-white rounded-xl overflow-hidden"
      >
        <input
          type="text"
          placeholder="Search Product By Name..."
          class="w-[95%] h-full outline-none px-5"
          v-model="name"
          ref="searchInput"
          @keyup="searchByName()"
        />

        <div
          class="w-[60px] flex items-center border-s bg-gray-100 justify-center rounded-e-xl h-full"
        >
          <Icon icon="ph:magnifying-glass" :ssr="true" class="text-2xl" />
        </div>
      </div>

      <div
        class="w-[30%] mb-3.5 h-14 border flex items-center justify-between bg-white rounded-xl overflow-hidden"
      >
        <input
          type="text"
          placeholder="Search Product By Barcode..."
          class="w-[95%] h-full outline-none px-5"
          v-model="barcode"
          ref="searchBarcode"
          @change="searchByBarcode()"
        />

        <div
          class="w-[60px] flex items-center border-s bg-gray-100 justify-center rounded-e-xl h-full"
        >
          <Icon icon="ph:barcode" :ssr="true" class="text-2xl" />
        </div>
      </div>
    </div>

    <div class="w-full min-h-[70vh] flex flex-col justify-between">
      <div v-if="loading === false">
        <div
          v-if="productsData.length > 0"
          class="mt-2 flex justify-center gap-3 flex-wrap"
        >
          <Card
            v-for="product in productsData"
            :key="product.id"
            :product="product"
            @click="addOrder(product)"
          />

          <!-- <button @click="printReceipt()">Print</button> -->
        </div>

        <el-empty v-else :image-size="200" />
      </div>
      <div v-else class="flex items-center justify-center w-full">
        <Loading />
      </div>

      <div>
        <Pagination :pagination="productsData" />
      </div>
    </div>
  </div>
</template>
