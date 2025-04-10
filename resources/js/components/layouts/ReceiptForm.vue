<script setup>
import { useReceiptStore } from '../../stores/receipt.js';
import formatPrice from '../../../core/helper/formatPrice.js';
import { defineAsyncComponent, ref, watch } from 'vue';
import { useMethodStore } from '../../stores/method.js';
import { useForm } from '@inertiajs/vue3';
import { onKeyStroke } from '@vueuse/core';
import { Icon } from '@iconify/vue/dist/iconify.js';

const ModalPayment = defineAsyncComponent(() =>
  import('../modal/ModalPayment.vue')
);

const ModalPrint = defineAsyncComponent(() =>
  import('../modal/ModalPrint.vue')
);

const receiptStore = useReceiptStore();
const method = useMethodStore();

const total = ref(null);

const form = useForm({
  products: [
    {
      id: '',
    },
  ],
});

const qtyNullFunc = (product) => {
  if (product.total === 0) {
    const filteredProduct = receiptStore.products.filter(
      (item) => item.id !== product.id
    );
    receiptStore.products = filteredProduct;
  }
};

const qtyFunc = (product) => {
  product.total = parseInt(product.total) || 0;

  if (product.total >= product.stock) {
    product.total = product.stock;
  }
};

const preventNonNumber = (event) => {
  // Daftar tombol yang diizinkan
  const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];

  if (isNaN(event.key) && !allowedKeys.includes(event.key)) {
    event.preventDefault();
  }
};

const submit = async () => {
  if (receiptStore.products.length > 0) {
    form.products = receiptStore.products;
    console.log(receiptStore.products);
    form.post('/transactions/addToCart', {
      onSuccess: () => {
        method.modalPaymentFnc();
      },
    });
  }
};

onKeyStroke('Tab', () => {
  if (receiptStore.products.length > 0) {
    submit();
  }
});

const clearData = () => {
  receiptStore.products = [];
};

const removeOrder = (id) => {
  const product = receiptStore.products.filter((item) => item.id !== id);
  receiptStore.products = product;
};

const increment = (id) => {
  const product = receiptStore.products.find((item) => item.id === id);
  if (product.stock > product.total) {
    product.total++;
  }
};

const decrement = (id) => {
  const product = receiptStore.products.find((item) => item.id === id);
  if (product.total < 2) {
    receiptStore.products = receiptStore.products.filter(
      (item) => item.id !== id
    );
  } else {
    product.total--;
  }
};

const totalPrice = () => {
  total.value = receiptStore.products.reduce(
    (acc, item) => acc + item.sell_price * item.total,
    0
  );
};

watch(
  () => receiptStore.products, // Data yang ingin dipantau
  () => {
    // Memanggil totalPrice jika ada perubahan
    totalPrice();
  },
  { deep: true } // Supaya mendeteksi perubahan elemen dalam array
);
</script>

<template>
  <div class="w-[25%] border-l bg-white right-0 z-30">
    <div
      class="fixed top-0 right-0 overflow-y-auto flex flex-col justify-between w-[25%] h-full"
    >
      <div class="flex flex-grow flex-col justify-between">
        <div class="h-20 px-3 border-b flex items-center justify-between">
          <div class="flex items-center gap-1.5">
            <Icon icon="ph:receipt" class="text-2xl" />
            <h1 class="text-xl font-bold">List Order</h1>
          </div>
          <button
            @click="clearData"
            class="h-9 border-[1.5px] hover:bg-gray-100 text-gray-700 border-gray-400 px-3 rounded-lg text-sm font-semibold"
          >
            Clear Order
          </button>
        </div>

        <div class="h-[60%] w-full overflow-y-auto">
          <div
            v-if="!receiptStore.products.length"
            class="flex items-center justify-center h-full"
          >
            <h1 class="text-2xl font-semibold">No order</h1>
          </div>

          <div v-else class="divide-y-2 divide-dashed">
            <div
              v-for="product in receiptStore.products"
              :key="product.id"
              v-memo="[product.id, product.total]"
              class="p-3 flex gap-2.5"
            >
              <div class="w-[150px] h-[80px]">
                <img
                  class="w-full h-full object-cover rounded-xl"
                  :src="'storage/' + product.image"
                  loading="lazy"
                  width="150"
                  height="80"
                />
              </div>
              <div class="w-full">
                <h1 class="font-semibold">
                  {{ product.name }}
                </h1>
                <p class="text-sm mb-2 text-gray-500">
                  {{ formatPrice(product.sell_price) }}
                </p>
                <div class="flex items-center justify-between">
                  <button
                    @click="removeOrder(product.id)"
                    class="h-7 w-7 flex hover:bg-gray-100 items-center justify-center rounded-full border-2"
                  >
                    <Icon icon="ph:trash" :ssr="true" />
                  </button>
                  <div
                    class="flex justify-between gap-3 bg-gray-100 py-1 px-1 min-w-[110px] rounded-full"
                  >
                    <button
                      @click="decrement(product.id)"
                      class="bg-white rounded-full flex items-center justify-center h-[25px] w-[25px]"
                    >
                      <Icon icon="ph:minus" :ssr="true" />
                    </button>
                    <input
                      @keydown="preventNonNumber($event)"
                      @input="qtyFunc(product)"
                      @change="qtyNullFunc(product)"
                      v-model="product.total"
                      type="number"
                      class="delete-number-spin bg-transparent w-7 text-center"
                      aria-label="quantity"
                    />
                    <button
                      @click="increment(product.id)"
                      class="bg-white rounded-full flex items-center justify-center h-[25px] w-[25px]"
                    >
                      <Icon icon="ph:plus" :ssr="true" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex-1 flex flex-col justify-between border-t">
          <div class="flex-1 flex justify-between flex-col">
            <div class="flex flex-col gap-2 p-3">
              <div class="flex justify-between">
                <p>Subtotal</p>
                <p>Rp 10.000</p>
              </div>

              <div class="flex justify-between">
                <p>Discount</p>
                <p>Rp 10.000</p>
              </div>
            </div>

            <div
              class="flex items-center border-t-2 border-dashed p-3 font-semibold justify-between"
            >
              <p class="text-xl">TOTAL</p>
              <p>{{ formatPrice(total) }}</p>
            </div>
          </div>

          <div
            class="flex items-center border-t font-semibold gap-3 p-3 justify-between h-20"
          >
            <button
              class="border-2 flex-1 flex gap-2.5 items-center hover:shadow-inner rounded-full p-1.5"
            >
              <div class="p-1 bg-slate-100 border rounded-full">
                <Icon icon="ph:user-list" class="text-2xl text-slate-500" />
              </div>
              <p class="text-sm">Customer</p>
            </button>
            <button
              class="border-2 flex-1 flex gap-2.5 items-center hover:shadow-inner rounded-full p-1.5"
            >
              <div class="p-1 bg-slate-100 border rounded-full">
                <Icon icon="ph:ticket" class="text-2xl text-slate-500" />
              </div>
              <p class="text-sm">Coupon</p>
            </button>
          </div>
        </div>
      </div>

      <button
        :disabled="receiptStore.products.length < 1 || form.processing"
        @click="submit"
        :class="
          receiptStore.products.length < 1 ? 'bg-violet-300' : 'bg-violet-400 '
        "
        class="font-semibold text-xl text-white w-full h-16 border-t"
      >
        Place Order
      </button>
    </div>
  </div>

  <ModalPayment :total="total" />

  <ModalPrint />
</template>
