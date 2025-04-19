<template>
  <div class="py-8 px-7 flex items-center justify-center w-full">
    <div class="px-10 py-8 w-full border bg-white rounded-lg">
      <div
        class="mb-7 flex flex-wrap items-center gap-4 md:justify-between pb-4"
      >
        <h1 class="text-3xl font-bold mb-1">Products</h1>

        <div class="flex gap-3 flex-wrap md:justify-between">
          <div
            class="md:w-[250px] w-full h-10 border flex items-center px-3 gap-3 bg-white rounded-lg overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" class="text-xl" />
            <input
              type="text"
              v-model="name"
              @keydown="searchProductName()"
              placeholder="Search Product..."
              class="h-full outline-none w-full placeholder:text-sm placeholder:font-medium"
            />
          </div>

          <PrimarySelect
            @change="categoryChange"
            :datas="categories"
            v-model="searchCat"
            class="md:basis-auto basis-[calc(50%-10px)]"
          />

          <Link
            href="/products/create"
            class="text-white border rounded-lg md:basis-auto basis-[calc(50%-10px)] h-10 bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon
              icon="ph:plus-bold"
              :ssr="true"
              class="text-xl flex-shrink-0"
            />
            <p class="flex-shrink-0">Add Data</p>
          </Link>
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="products.data.length > 0"
          :data="products.data"
          :header="headerConfig"
        >
          <template v-slot:image="{ row: i }">
            <img
              class="w-10"
              :src="'storage/' + i.image"
              loading="lazy"
              alt=""
            />
          </template>
          <template v-slot:category="{ row: i }">
            <p>{{ i.category.name }}</p>
          </template>
          <template v-slot:buy_price="{ row: i }">
            <p>{{ formatPrice(i.buy_price) }}</p>
          </template>
          <template v-slot:sell_price="{ row: i }">
            <p>{{ formatPrice(i.sell_price) }}</p>
          </template>
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-2">
              <Link
                :href="`/products/${i.id}/edit`"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-blue-400 text-sm rounded-md"
              >
                <p>Edit</p>
              </Link>
              <button
                @click="modalButtonFnc(i.id)"
                class="py-2 px-4 flex items-center gap-1.5 font-semibold hover:bg-gray-100 border border-gray-200 text-red-400 text-sm rounded-md"
              >
                <p>Delete</p>
              </button>
            </div>
          </template>
        </DataTable>

        <el-empty v-else :image-size="200" />

        <Pagination :pagination="products" />
      </div>
    </div>

    <Modal v-model="modalAlert">
      <template #header> Are you absolustly sure? </template>
      <template #description>
        Are you sure you want to delete this product? Once deleted, this action
        cannot be undone and the product will be permanently removed.</template
      >
      <template #action>
        <PrimaryButton
          @click="modalAlert = false"
          text="Cancel"
          class="border border-gray-400 bg-transparent !text-gray-500"
        />
        <PrimaryButton
          @click="destroy(dataId)"
          text="Delete"
          class="bg-red-500 text-white"
        />
      </template>
    </Modal>
  </div>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import formatPrice from '../../../core/helper/formatPrice';
import { Link, router } from '@inertiajs/vue3';
import { useMethodStore } from '../../stores/method';
import { ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import DataTable from '../../components/layouts/DataTable.vue';
import PrimaryButton from '../../components/ui/PrimaryButton.vue';
import PrimarySelect from '../../components/ui/PrimarySelect.vue';
import { Icon } from '@iconify/vue/dist/iconify.js';
import Modal from '../../components/modal/Modal.vue';
import { debounce } from 'lodash';

// Layout
defineOptions({ layout: Layout });

// Props
const props = defineProps({
  products: Object,
  categories: Object,
});

// Config Table
const headerConfig = [
  { key: 'barcode', label: 'Barcode' },
  { key: 'name', label: 'Name' },
  { key: 'category', label: 'Category' },
  { key: 'buy_price', label: 'Buy Price' },
  { key: 'sell_price', label: 'Sell Price' },
  { key: 'stock', label: 'stock' },
  { key: 'image', label: 'image' },
];

// Define Store
const method = useMethodStore();

// State API
const name = ref(new URL(document.location).searchParams.get('name') || '');
const searchCat = ref('');
const loading = ref();

// State Modal
const modalAlert = ref(false);
const dataId = ref(0);

// Function Search Product Data (API)
const searchProductName = debounce(() => {
  router.get('/products/searchProductName', {
    name: name.value,
  });
}, 500);

// Function Filter by Category
const categoryChange = () => {
  router.get('/products', {
    category_id: searchCat.value,
  });
};

// Function Modal Alert
const modalButtonFnc = (id) => {
  modalAlert.value = true;
  dataId.value = id;
};

// Function Delete
const destroy = (id) => {
  loading.value = true;

  router.delete(`/products/${id}`, {
    preserveState: false,
    onSuccess: () => {
      method.modalFnc();
    },
    onError: (errors) => {
      console.error(errors);
    },
    onFinish: () => {
      loading.value = false;
    },
  });
};
</script>

<style lang="scss" scoped></style>
