<script setup>
import Layout from '../../Layouts/Layout.vue';
import TextInput from '../../components/ui/TextInput.vue';
import TextAreaInput from '../../components/ui/TextAreaInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref } from 'vue';
import DropdownInput from '../../components/ui/DropdownInput.vue';

// Layout
defineOptions({
  layout: Layout,
});

// Props
const props = defineProps({
  products: Object,
  suppliers: Object,
  stock_opname: Object,
});

// State API
const name = ref('');
const selectedProduct = ref(null);
const productsData = ref(props.products);
const loading = ref(false);

// Function Product Dropdown (API)
const productDropdown = debounce(() => {
  if (!name.value) {
    productsData.value = props.products;
    return;
  }

  form.product_id = '';
  loading.value = true;

  axios
    .post('/product/dropDownProduct', { name: name.value })
    .then((response) => {
      if (response.data.success && response.data.data.length > 0) {
        productsData.value = response.data.data;
      } else {
        productsData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
      productsData.value = [];
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

// Form
const form = useForm({
  product_id: '',
  supplier_id: '',
  detail: '',
  qty: '',
});

// Function Select Product
const selectProduct = (product) => {
  selectedProduct.value = product;
  name.value = product.name;
  form.product_id = product.id;
};

// Function Submit
const submit = () => {
  form.post('/stock-in');
};
</script>

<template>
  <div class="py-8 px-7 w-full flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold mb-1">Add Stock in</h1>
      </div>

      <form @submit.prevent="submit" class="w-full flex flex-col gap-3">
        <DropdownInput
          label="Product Name"
          v-model="name"
          :items="productsData"
          :loading="loading"
          placeholder="Product Name"
          @keydown="productDropdown"
          @select="selectProduct"
          :message="form.errors.product_id"
        >
          <template #item="{ item }">
            <div>{{ item.name }}</div>
            <div class="text-sm text-gray-500">Stock: {{ item.stock }}</div>
          </template>
        </DropdownInput>

        <div class="mb-2">
          <label for="" class="mb-1 text-[13px]">Supplier</label>
          <div
            :class="
              form.errors.supplier_id ? 'border-red-500' : 'border-gray-300'
            "
            class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
          >
            <select
              v-model="form.supplier_id"
              name=""
              id=""
              class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
            >
              <option value="" disabled selected>Supplier</option>
              <option v-for="supplier in props.suppliers" :value="supplier.id">
                {{ supplier.name }}
              </option>
            </select>
          </div>
          <small v-if="form.errors.supplier_id" class="text-red-500">{{
            form.errors.supplier_id
          }}</small>
        </div>

        <TextInput
          name="Stock"
          type="number"
          v-model="form.qty"
          placeholder="Your new stock qty"
          :message="form.errors.qty"
        />

        <TextAreaInput
          name="Detail"
          v-model="form.detail"
          placeholder="Your supplier description"
          :message="form.errors.detail"
        />

        <div class="flex justify-end gap-3">
          <Link
            href="/stock-in"
            class="h-10 px-3 flex items-center bg-violet-100 rounded-lg font-semibold text-violet-400"
          >
            Back
          </Link>

          <button
            type="submit"
            :disabled="form.processing"
            :class="
              form.processing
                ? 'cursor-not-allowed bg-violet-300 text-gray-200'
                : ''
            "
            class="h-10 px-3 bg-violet-400 rounded-lg font-semibold text-white"
          >
            {{ form.processing ? 'Loading...' : 'Submit' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
