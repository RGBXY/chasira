<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold mb-1">Add Stock Out</h1>
      </div>

      <form @submit.prevent="submit" class="w-full flex flex-col gap-3">
        <DropdownInput
          label="Product Name"
          v-model="name"
          :items="productsData"
          :loading="loading"
          placeholder="Product Name"
          @keydown="debouncedSearch"
          @select="selectProduct"
          :message="form.errors.product_id"
        >
          <template #item="{ item }">
            <div>{{ item.name }}</div>
            <div class="text-sm text-gray-500">Stock: {{ item.stock }}</div>
          </template>
        </DropdownInput>

        <TextInput
          name="Display Stock"
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
            href="/suppliers"
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

defineOptions({
  layout: Layout,
});

const name = ref('');
const productsData = ref(props.products);
const loading = ref(false);
const selectedProduct = ref(null);

const props = defineProps({
  products: Array,
});

const debouncedSearch = debounce(() => {
  if (!name.value) {
    productsData.value = [];
    return;
  }

  form.product_id = '';
  loading.value = true;

  axios
    .post('/stock-out/searchProduct', { name: name.value })
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

const form = useForm({
  product_id: '',
  qty: '',
  detail: '',
});

const selectProduct = (product) => {
  selectedProduct.value = product;
  name.value = product.name;
  form.product_id = product.id;
};

const submit = () => {
  form.post('/stock-out');
};

watch(
  () => productsData.value,
  (newVal) => {
    form.product_id = newVal?.id || '';
  }
);
</script>
