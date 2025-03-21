<script setup>
import { useMethodStore } from '../../stores/method';
import formatPrice from '../../../core/helper/formatPrice';
import { computed, ref } from 'vue';
import { useReceiptStore } from '../../stores/receipt';
import { useForm } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { Icon } from '@iconify/vue/dist/iconify.js';

const method = useMethodStore();
const receipt = useReceiptStore();

const formGlobal = useForm({});

const { customers } = storeToRefs(receipt);

const props = defineProps({
  total: Number,
});

const modalClose = () => {
  formGlobal.post('/transactions/destroyCart', {
    onSuccess: () => {
      method.modalPaymentFnc();
    },
  });
};

const form = useForm({
  cash: 0,
  change: 0,
  discount: 0,
  total: 0,
  customer_id: '',
  grand_total: 0,
});

const grandTotal = computed(() => props.total - form.discount);
const change = computed(() => form.cash - props.total);

const pay = () => {
  form.change = change;
  form.total = props.total;
  form.grand_total = grandTotal;

  const isPaymentValid = computed(() => {
    return cash.value >= props.total;
  });

  if (isPaymentValid.value) {
    form.post('/', {
      onSuccess: () => {
        receipt.change = cash.value - props.total;
        method.modalPrintFnc(cash.value);
        method.modalPaymentFnc();
      },
    });
  }
};
</script>

<template>
  <div
    :class="
      method.modalPyamentStat ? ' opacity-100 visible' : 'opacity-0 invisible'
    "
    class="bg-black backdrop-blur-sm bg-opacity-40 fixed transition-all duration-500 left-0 top-0 w-full h-screen z-50 flex items-end justify-center"
  >
    <div
      :class="method.modalPyamentStat ? ' h-[85%]' : ' h-0'"
      class="w-full bg-white flex relative transition-all overflow-y-auto duration-500 justify-center p-10 rounded-t-[80px]"
    >
      <button
        @click="modalClose"
        class="rounded-full p-1 absolute right-14 border border-black"
      >
        <Icon icon="ph:x" class="text-2xl" />
      </button>
      <form @submit.prevent="pay()" class="bg-white w-full max-w-xl">
        <div class="h-16 mb-5 flex items-center justify-between border-b">
          <div class="flex gap-4">
            <h1 class="text-2xl font-semibold">Payment</h1>
          </div>
        </div>
        <div class="">
          <div
            class="w-full flex flex-col items-center mb-5 justify-center min-h-[140px] rounded-xl bg-gradient-to-b from-violet-400 to-violet-600"
          >
            <p class="text-white font-semibold text-xl leading-none mb-1.5">
              Total Payment
            </p>
            <h1 class="text-4xl font-extrabold text-white">
              {{ formatPrice(total) }}
            </h1>
          </div>

          <div class="flex flex-col gap-4">
            <div>
              <label for="customer" class="text-lg font-bold mb-1.5">
                Customers :
              </label>

              <div
                :class="[
                  form.errors.customer_id
                    ? 'border-red-600'
                    : 'border-violet-500',
                ]"
                class="h-11 rounded-lg bg-white px-2 border-[1.5px]"
              >
                <select
                  required
                  v-model="form.customer_id"
                  name="customer"
                  id="customer"
                  class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
                >
                  <option value="" disabled selected>Customers</option>
                  <option v-for="customer in customers" :value="customer.id">
                    {{ customer.name }}
                  </option>
                </select>
              </div>

              <p
                v-if="form.errors.customer_id"
                class="text-sm mt-1.5 text-red-600"
              >
                {{ form.errors.customer_id }}
              </p>
            </div>

            <div>
              <label for="cash" class="text-lg font-bold mb-1.5">Cash :</label>

              <input
                required
                type="number"
                v-model="form.cash"
                id="cash"
                placeholder="Cash Amount"
                :class="[
                  form.errors.cash ? 'border-red-600' : 'border-violet-500',
                ]"
                class="w-full focus:border-2 text-sm h-11 px-2 border rounded-md outline-none"
              />

              <p v-if="form.errors.cash" class="text-sm mt-1.5 text-red-600">
                {{ form.errors.cash }}
              </p>
            </div>

            <div>
              <label for="voucher" class="text-lg font-bold mb-1.5">
                Voucher Token :
              </label>

              <input
                type="number"
                v-model="form.discount"
                id="voucher"
                placeholder="Voucher Token"
                class="w-full focus:border-2 border-violet-500 text-sm h-11 px-2 border rounded-md outline-none"
              />
            </div>

            <button
              type="submit"
              class="h-11 px-4 mt-2 font-semibold text-sm transition-all bg-violet-400 hover:bg-violet-500 rounded-md text-white"
            >
              Charge
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
