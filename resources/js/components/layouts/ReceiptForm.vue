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

const subtotal = ref(null);
const total = ref(null);
const discount = ref(0);

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
    form.post('/transactions/addToCart', {
      onSuccess: () => {
        method.modalPaymentFnc();
        receiptStore.refresh = false;
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
  receiptStore.customer = null;
  receiptStore.discountNominal = 0;
  receiptStore.discountPercent = null;
  discount.value = 0;
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
  subtotal.value = receiptStore.products.reduce(
    (acc, item) => acc + item.sell_price * item.total,
    0
  );

  total.value = subtotal.value - discount.value;
};

const totalAfterDiscount = () => {
  if (receiptStore.discountPercent !== null) {
    total.value =
      subtotal.value -
      subtotal.value * (receiptStore.discountPercent.discount / 100);
    discount.value = subtotal.value - total.value;
  }
};

watch(
  () => receiptStore.products,
  () => {
    discount.value = 0;

    totalPrice();

    if (receiptStore.discountPercent !== null) {
      totalAfterDiscount();
    }

    receiptStore.subtotal = subtotal.value;
  },
  { deep: true }
);

watch(
  () => receiptStore.discountNominal,
  () => {
    receiptStore.discountPercent = null;
    discount.value = receiptStore.discountNominal;
    total.value = subtotal.value - discount.value;
  },
  { deep: true }
);

watch(
  () => receiptStore.discountPercent,
  () => {
    totalAfterDiscount();
  },
  { deep: true }
);
</script>

<template>
  <div
    :class="receiptStore.receiptStat ? 'block' : 'hidden'"
    class="lg:w-[25%] w-full lg:static fixed h-screen lg:block left-0 border-l right-0 z-30"
  >
    <div
      class="fixed top-0 right-0 overflow-y-auto flex flex-col bg-white justify-between lg:w-[25%] w-full h-full"
    >
      <div class="flex flex-grow flex-col justify-between">
        <div class="h-20 px-3 border-b flex items-center justify-between">
          <div class="flex gap-5">
            <button
              @click="receiptStore.receiptStatFnc()"
              class="border-2 lg:hidden rounded-full p-2 text-xl"
            >
              <Icon :ssr="true" icon="ph:x" />
            </button>

            <div class="flex items-center gap-1.5">
              <Icon icon="ph:receipt" class="text-2xl" />
              <h1 class="text-xl font-bold">List Order</h1>
            </div>
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
                <p>{{ formatPrice(subtotal) }}</p>
              </div>

              <div class="flex justify-between">
                <p>
                  {{
                    receiptStore.discountPercent === null
                      ? 'Discount'
                      : `Discont - ${receiptStore.discountPercent.name}`
                  }}
                </p>
                <p>- {{ formatPrice(discount) }}</p>
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
              @click="method.modalCustomerFnc()"
              class="border-2 flex-1 flex gap-2.5 items-center hover:shadow-inner rounded-full p-1.5"
            >
              <div
                :class="
                  receiptStore.customer !== null
                    ? ' bg-green-100'
                    : 'bg-slate-100'
                "
                class="p-1 border rounded-full"
              >
                <Icon
                  :icon="
                    receiptStore.customer !== null
                      ? 'ph:check-bold'
                      : 'ph:user-list'
                  "
                  :class="
                    receiptStore.customer !== null
                      ? ' text-green-500'
                      : 'text-slate-500'
                  "
                  class="text-2xl"
                />
              </div>
              <p class="text-sm">
                {{
                  receiptStore.customer !== null
                    ? receiptStore.customer.name
                    : 'Customer'
                }}
              </p>
            </button>
            <button
              @click="method.modalDiscountFnc()"
              class="border-2 flex-1 flex gap-2.5 items-center hover:shadow-inner rounded-full p-1.5"
            >
              <div
                :class="
                  receiptStore.discountPercent !== null
                    ? ' bg-green-100'
                    : 'bg-slate-100'
                "
                class="p-1 border rounded-full"
              >
                <Icon
                  :icon="
                    receiptStore.discountPercent !== null
                      ? 'ph:check-bold'
                      : 'ph:seal-percent'
                  "
                  :class="
                    receiptStore.discountPercent !== null
                      ? ' text-green-500'
                      : 'text-slate-500'
                  "
                  class="text-2xl"
                />
              </div>
              <p class="text-sm">
                {{
                  receiptStore.discountPercent !== null
                    ? receiptStore.discountPercent.name
                    : 'Discount'
                }}
              </p>
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

  <ModalPayment :total="subtotal" :discount="discount" :grand_total="total" />

  <ModalPrint />
</template>
