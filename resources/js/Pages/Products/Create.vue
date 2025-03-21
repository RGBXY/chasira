<script setup>
import Layout from '../../Layouts/Layout.vue';
import TextInput from '../../components/ui/TextInput.vue';
import TextAreaInput from '../../components/ui/TextAreaInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import DropdownInput from '../../components/ui/DropdownInput.vue';
import { ref } from 'vue';
import { debounce } from 'lodash';

const name = ref('');
const categoriesData = ref([]);
const selectedCategories = ref(null);
const loading = ref(false);

const form = useForm({
  name: '',
  category_id: '',
  barcode: '',
  buy_price: null,
  sell_price: null,
  stock: 0,
  description: '',
  image: null,
});

const change = (e) => {
  form.image = e.target.files[0];
};

const debouncedSearch = debounce(() => {
  if (!name.value) {
    categoriesData.value = [];
    return;
  }

  form.product_id = '';
  loading.value = true;

  axios
    .post('/categories/dropDownCategory', { name: name.value })
    .then((response) => {
      console.log(response);
      if (response.data.success && response.data.data.length > 0) {
        categoriesData.value = response.data.data;
      } else {
        categoriesData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
      categoriesData.value = [];
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

const selectCategories = (category) => {
  selectedCategories.value = category;
  name.value = category.name;
  form.category_id = category.id;
};

const submit = () => {
  form.post('/products');
};

const props = defineProps({
  categories: Object,
  outlets: Object,
});
</script>

<template>
  <Layout>
    <div class="w-full py-8 px-7 flex items-center justify-center">
      <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
        <div class="mb-6 flex items-center justify-between">
          <h1 class="text-3xl font-bold mb-1">Add Products</h1>
        </div>

        <form @submit.prevent="submit" class="w-full flex flex-col gap-3">
          <div class="mb-2">
            <DropdownInput
              label="Category Name"
              v-model="name"
              :items="categoriesData"
              :loading="loading"
              placeholder="Category Name"
              @keydown="debouncedSearch"
              @select="selectCategories"
              :message="form.errors.category_id"
            >
            </DropdownInput>
          </div>

          <TextInput
            name="Barcode"
            type="text"
            v-model="form.barcode"
            placeholder="Your Barcode"
            :message="form.errors.barcode"
          />

          <TextInput
            name="Product Name"
            type="text"
            v-model="form.name"
            placeholder="Your product name"
            :message="form.errors.name"
          />

          <TextInput
            name="Buy Price"
            type="number"
            v-model="form.buy_price"
            placeholder="Product buy price"
            :message="form.errors.buy_price"
          />

          <TextInput
            name="Sell Price"
            type="number"
            v-model="form.sell_price"
            placeholder="Product sell price"
            :message="form.errors.sell_price"
          />

          <TextAreaInput
            v-model="form.description"
            name="Product Description"
            placeholder="Your Product Descriptuon"
            :message="form.errors.description"
          />

          <div class="mb-5">
            <p for="" class="mb-1.5 text-[13px]">Image</p>
            <label
              class="h-32 flex flex-col items-center justify-center border-gray-400 rounded-lg border-2 border-dashed"
            >
              <input type="file" class="" @input="change" />
            </label>
            <small v-if="form.errors.image" class="text-red-500">{{
              form.errors.image
            }}</small>
          </div>

          <div class="flex justify-end gap-3">
            <Link
              href="/products"
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
  </Layout>
</template>
