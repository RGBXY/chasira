<template>
  <div
    :class="
      method.modalDiscountStat ? 'opacity-100 visible' : 'opacity-0 invisible'
    "
    @click="method.modalDiscountClose()"
    class="fixed top-0 left-0 bg-black backdrop-blur-sm bg-opacity-40 transition-all duration-500 z-50 w-full h-screen flex justify-center items-center"
  >
    <div
      @click.stop
      class="lg:w-[30%] md:w-[50%] w-[90%] max-h-[550px] overflow-y-auto bg-white border rounded-lg"
    >
      <div
        class="h-20 border-b sticky top-0 bg-white flex items-center justify-between px-6"
      >
        <h3 class="text-2xl font-semibold text-gray-900">Apply Discount</h3>
        <button @click="method.modalDiscountClose()" class="p-2">
          <Icon icon="ph:x" :ssr="true" class="text-xl text-slate-700" />
        </button>
      </div>

      <div class="p-5">
        <div class="space-y-5">
          <div
            class="w-full border-b-2 px-1 pb-6 border-gray-100 text-start space-y-2 group transition-all duration-300"
          >
            <h1 class="text-lg font-semibold text-slate-800 leading-none">
              Input Discount
            </h1>
            <p class="text-gray-500">Input discount manualy</p>
            <div
              class="overflow-hidden flex mt-1 gap-2 justify-between transition-all duration-200 w-full h-10"
              @click.stop
            >
              <input
                type="number"
                v-model="discountNominal"
                placeholder="5000"
                class="h-full px-2 border rounded-md flex-1 outline-none"
              />
              <button
                @click="discountNominalFnc()"
                class="bg-violet-400 px-3 rounded-md text-white"
              >
                Submit
              </button>
            </div>
          </div>

          <div
            class="w-full border-b-2 px-1 pb-6 border-gray-100 text-start space-y-2 group transition-all duration-300"
          >
            <h1 class="text-lg font-semibold leading-none text-slate-800">
              Input Discount Code
            </h1>
            <p class="text-gray-500">Input discount code manualy</p>

            <div
              class="overflow-hidden mt-1 flex gap-2 justify-between transition-all duration-200 w-full h-10"
              @click.stop
            >
              <input
                v-model="code"
                type="text"
                placeholder="ABC450 mt (Coupon Code)"
                class="h-full px-2 rounded-md border flex-1 outline-none"
              />
              <button
                @click="searchDiscountCode()"
                class="bg-violet-400 px-3 rounded-md text-white"
              >
                Submit
              </button>
            </div>
          </div>

          <div class="space-y-3 pt-1">
            <button
              @click="discountPercentFnc(discount)"
              v-for="discount in props.discounts"
              class="w-full flex items-center justify-between text-start border border-gray-200 p-6 rounded-md group hover:bg-gray-100"
            >
              <div>
                <h1 class="text-xl font-semibold text-slate-800">
                  {{ discount.name }} ({{ discount.code }})
                </h1>
                <div class="flex divide-x divide-gray-400">
                  <p class="text-gray-500 pe-3">
                    Discount {{ discount.discount }}%
                  </p>
                  <p class="text-gray-500 ps-3">
                    {{
                      discount.customer_only == 1
                        ? 'Member Only'
                        : 'All Customer'
                    }}
                  </p>
                </div>
              </div>
              <Icon
                icon="ph:arrow-right"
                :ssr="true"
                class="text-2xl text-slate-700"
              />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue/dist/iconify.js';
import { useMethodStore } from '../../stores/method';
import { ref } from 'vue';
import { debounce } from 'lodash';
import { useReceiptStore } from '../../stores/receipt';

const method = useMethodStore();
const receipt = useReceiptStore();

const props = defineProps({
  discounts: Array,
});

const code = ref('');
const discountNominal = ref('');
const loading = ref(false);

// Function Search Category Data (API)
const searchDiscountCode = debounce(() => {
  if (code.value.trim() == '') {
    discountData.value = props.customers;
    return;
  }

  loading.value = true;

  axios
    .post('/discount-transactions/searchDiscountCode', {
      code: code.value,
      customer_id: receipt?.customer?.id,
      subtotal: receipt.subtotal,
    })
    .then((response) => {
      if (response.data.success) {
        receipt.discountPercent = response.data.data;
        method.modalDiscountClose();
      } else {
        method.toasterFnc(response.data.message || 'Coupon code is not valid');
      }
    })
    .catch((error) => {
      console.error(error);
      method.toasterFnc(error);
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

const discountNominalFnc = () => {
  receipt.discountNominal = 0;
  receipt.discountNominal = discountNominal.value;
  discountNominal.value = 0;
  method.modalDiscountClose();
};

const discountPercentFnc = (discount) => {
  receipt.discountPercent = null;
  code.value = discount.code;
  searchDiscountCode();
};
</script>
