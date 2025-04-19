<script setup>
import CategoryCard from '../../components/card/CategoryCard.vue';
import MainLayout from '../../Layouts/MainLayout.vue';
import { defineAsyncComponent, onBeforeUnmount, onMounted, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { useReceiptStore } from '../../stores/receipt';
import { useMethodStore } from '../../stores/method';
import axios from 'axios';
import { onKeyStroke } from '@vueuse/core';
import { debounce } from 'lodash';
import { Icon } from '@iconify/vue/dist/iconify.js';
import ModalDiscount from '../../components/modal/ModalDiscount.vue';

// Layout
defineOptions({ layout: MainLayout });

const Pagination = defineAsyncComponent(() =>
  import('../../components/ui/Pagination.vue')
);

const ModalCustomer = defineAsyncComponent(() =>
  import('../../components/modal/ModalCustomer.vue')
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
  discounts: Object,
});

// State API

const loading = ref(false);

const barcode = ref('');
const name = ref(new URL(document.location).searchParams.get('name') || '');

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

    if (window.innerWidth > 1024) {
      searchBarcode.value.focus();
    }
  } else if (order.stock === 0) {
    method.toasterFnc('Product stock is empty');
    barcode.value = '';

    if (window.innerWidth > 1024) {
      searchBarcode.value.focus();
    }
  }
};

// Search By Name
const searchProductsName = debounce(() => {
  router.get(
    '/transactions/searchProductName',
    {
      name: name.value,
    },
    {
      preserveState: true,
      only: ['products'],
    }
  );
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

onMounted(() => {
  method.sideBarStat = false;
});

onBeforeUnmount(() => {
  if (page.url !== '/') {
    receiptStore.products = [];
    receiptStore.customer = null;
  }
});
</script>

<template>
  <div class="px-7 w-full">
    <div
      class="flex items-center w-full mb-5 lg:mb-0 gap-2.5 py-3 overflow-x-auto no-scrollbar"
    >
      <CategoryCard :data="props.categories" />
    </div>

    <div
      v-if="
        receiptStore.products.length > 0 && receiptStore.receiptStat == false
      "
      class="fixed bottom-0 left-0 right-0 z-40"
    >
      <button
        @click="receiptStore.receiptStatFnc()"
        class="flex items-center lg:hidden font-semibold text-white text-xl justify-center gap-2 w-full h-14 bg-gradient-to-b from-violet-500 bg-primary"
      >
        <Icon ssr="true" icon="ph:shopping-cart-bold" />
        <p class="">Cart</p>
        {{ receiptStore.products.length }}
      </button>
    </div>

    <div class="flex flex-col md:flex-row gap-3">
      <div
        class="lg:w-[70%] md:flex-1 md:mb-3.5 h-14 border flex items-center justify-between bg-white rounded-xl overflow-hidden"
      >
        <input
          type="text"
          placeholder="Search Product By Name..."
          class="w-[95%] h-full outline-none px-5"
          v-model="name"
          ref="searchInput"
          @keyup="searchProductsName()"
        />

        <div
          class="w-[60px] flex items-center border-s bg-gray-100 justify-center rounded-e-xl h-full"
        >
          <Icon icon="ph:magnifying-glass" :ssr="true" class="text-2xl" />
        </div>
      </div>

      <div
        class="lg:w-[30%] md:flex-1 mb-3.5 h-14 border flex items-center justify-between bg-white rounded-xl overflow-hidden"
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
          v-if="products.data.length > 0"
          class="mt-2 flex justify-center gap-3 flex-wrap"
        >
          <Card
            v-for="product in products.data"
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
        <Pagination :pagination="products" />
      </div>
    </div>

    <ModalCustomer :customers="props.customers" />
    <ModalDiscount :discounts="props.discounts" />
  </div>
</template>
