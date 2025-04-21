<script setup>
import { useMethodStore } from '../../stores/method';
import formatPrice from '../../../core/helper/formatPrice';
import { computed, ref } from 'vue';
import { useReceiptStore } from '../../stores/receipt';
import { useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue/dist/iconify.js';

const method = useMethodStore();
const receipt = useReceiptStore();

const formGlobal = useForm({});

const props = defineProps({
  total: Number,
  discount: Number,
  grand_total: Number,
});

const modalClose = () => {
  formGlobal.post('/transactions/destroyCart', {
    onSuccess: () => {
      method.modalPaymentFnc();
    },
  });
};

const cash = ref(0);
const change = computed(() => cash.value - props.grand_total);

const pay = async () => {
  const isPaymentValid = computed(() => {
    return cash.value >= props.grand_total;
  });

  if (isPaymentValid.value) {
    try {
      const payload = {
        cash: cash.value,
        change: change.value,
        total: props.total,
        discount: props.discount,
        grand_total: props.grand_total,
        customer_id: receipt.customer === null ? 1 : receipt.customer.id,
      };

      const response = await axios.post('/', payload);
      method.modalPrintFnc(cash.value);
      method.modalPaymentFnc();

      receipt.change = cash.value - props.grand_total;
      cash.value = 0;
      receipt.transaction_id = response.data.transaction_id;
      receipt.customer = null;
      receipt.discountNominal = 0;
      receipt.discountPercent = null;
      receipt.products = [];
      receipt.refresh = true;
    } catch (error) {
      console.error(
        'Gagal menyimpan transaksi:',
        error.response?.data || error
      );
    }
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
      class="w-full bg-white flex relative transition-all overflow-y-auto duration-500 justify-center p-10 rounded-t-[40px] md:rounded-t-[80px]"
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
              {{ formatPrice(grand_total) }}
            </h1>
          </div>

          <div class="flex flex-col gap-4">
            <div>
              <label for="cash" class="text-lg font-bold mb-1.5">Cash :</label>

              <input
                required
                type="number"
                v-model="cash"
                id="cash"
                placeholder="Cash Amount"
                class="w-full focus:border-2 text-sm h-11 px-2 border rounded-md outline-none"
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
