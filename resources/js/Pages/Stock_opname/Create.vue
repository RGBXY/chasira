<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold mb-1">Add Stock Opname</h1>
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

        <TextInput
          name="System Stock"
          type="number"
          :disabled="true"
          v-model="form.system_stock"
          placeholder="Product System stock"
          :message="form.errors.system_stock"
        />

        <TextInput
          name="Actual Stock"
          type="number"
          v-model="form.actual_stock"
          placeholder="Your new stock qty"
          :message="form.errors.actual_stock"
        />

        <TextAreaInput
          name="Detail"
          v-model="form.description"
          placeholder="Your supplier description"
          :message="form.errors.description"
        />

        <div class="flex justify-end gap-3">
          <Link
            href="/stock-opname"
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

<script setup>
import Layout from '../../Layouts/Layout.vue';
import TextInput from '../../components/ui/TextInput.vue';
import TextAreaInput from '../../components/ui/TextAreaInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';
import DropdownInput from '../../components/ui/DropdownInput.vue';

// Layout
defineOptions({
  layout: Layout,
});

// Props
const props = defineProps({
  products: Array,
});

// State API
const name = ref('');
const productsData = ref(props.products);
const loading = ref(false);
const selectedProduct = ref(null);

// Function Product Dropdown (API)
const productDropdown = debounce(() => {
  if (!name.value) {
    productsData.value = props.products;
    return;
  }

  form.product_id = '';
  loading.value = true;

  axios
    .post('/products/dropDownProduct', { name: name.value })
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
  actual_stock: '',
  system_stock: '',
  description: '',
});

// Function Select Product
const selectProduct = (product) => {
  selectedProduct.value = product;
  name.value = product.name;
  form.product_id = product.id;
  form.system_stock = product.stock;
};

// Function Submit
const submit = () => {
  form.post('/stock-opname');
};

// Watch for update form.product_id
watch(
  () => productsData.value,
  (newVal) => {
    form.product_id = newVal?.id || '';
  }
);
</script>
