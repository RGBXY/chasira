<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useMethodStore } from '../../stores/method';
import { ref } from 'vue';
import Pagination from '../../components/ui/Pagination.vue';
import DataTable from '../../components/layouts/DataTable.vue';
import Layout from '../../Layouts/Layout.vue';
import { Icon } from '@iconify/vue/dist/iconify.js';
import { debounce } from 'lodash';
import PrimarySelect from '../../components/ui/PrimarySelect.vue';
import Modal from '../../components/modal/Modal.vue';
import PrimaryButton from '../../components/ui/PrimaryButton.vue';
import OutlineButton from '../../components/ui/OutlineButton.vue';

// Layout
defineOptions({ layout: Layout });

// Props
const props = defineProps({
  categories: Object,
});

// Define Store
const method = useMethodStore();

// State API
const categoriesData = ref(props.categories.data);
const loading = ref(false);
const name = ref('');

// State Modal
const modalAlert = ref(false);
const dataId = ref(0);

// Config Select Input
const selectConfig = [
  { id: 'latest', name: 'Latest' },
  { id: 'most_products', name: 'Most Products' },
];

// Config Table
const headerConfig = [
  { key: 'name', label: 'Name' },
  { key: 'products_count', label: 'Total Product' },
  { key: 'description', label: 'Description' },
];

// Function Search Category Data (API)
const searchCategoryName = debounce(() => {
  if (name.value.trim() == '') {
    categoriesData.value = props.categories.data;
    return;
  }

  loading.value = true;

  axios
    .post('/categories/searchCategoryName', {
      name: name.value,
    })
    .then((response) => {
      console.log(response.data.data.data);
      if (response.data.success && response.data.data.data.length > 0) {
        categoriesData.value = response.data.data.data;
      } else {
        categoriesData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

// State Select Input
const filter = ref(new URL(document.location).searchParams.get('sort') || '');

// Function Select Input
const filterChange = () => {
  router.get('/categories', {
    sort: filter.value,
  });
};

// Function Modal ALert
const modalButtonFnc = (id) => {
  modalAlert.value = true;
  dataId.value = id;
};

// Function Delete
const destroy = (id) => {
  loading.value = true;

  router.delete(`/categories/${id}`, {
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

<template>
  <div class="py-8 px-7 flex items-center justify-center w-full">
    <div class="px-8 py-8 w-full border bg-white rounded-lg">
      <div class="mb-7 flex items-center justify-between pb-4">
        <h1 class="text-3xl font-bold mb-1">Categories</h1>

        <div class="flex gap-3 justify-between">
          <div
            class="w-[250px] h-10 border flex items-center px-3 gap-3 bg-white rounded-lg overflow-hidden"
          >
            <Icon icon="ph:magnifying-glass" :ssr="true" class="text-xl" />
            <input
              type="text"
              v-model="name"
              @keydown="searchCategoryName()"
              placeholder="Search Category..."
              class="h-full outline-none w-full placeholder:text-sm placeholder:font-medium"
            />
          </div>

          <PrimarySelect
            :datas="selectConfig"
            v-model="filter"
            @change="filterChange()"
          />

          <Link
            href="/categories/create"
            class="text-white border rounded-lg bg-violet-400 flex items-center justify-center gap-2 px-4"
          >
            <Icon icon="ph:plus-bold" :ssr="true" />
            <p>Add Data</p>
          </Link>
        </div>
      </div>

      <div class="w-full">
        <DataTable
          v-if="categoriesData.length > 0"
          :data="categoriesData"
          :header="headerConfig"
        >
          <template v-slot:products_count="{ row: i }">
            <div
              class="bg-violet-50 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
            >
              {{ i.products_count }}
            </div>
          </template>
          <template v-slot:actions="{ row: i }">
            <div class="flex items-start gap-1">
              <Link
                :href="`/categories/${i.id}/edit`"
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

        <Pagination :pagination="categoriesData" />
      </div>
    </div>

    <Modal v-model="modalAlert">
      <template #header> Are you absolustly sure? </template>
      <template #description>
        Are you sure you want to delete this product? Once deleted, this action
        cannot be undone and the product will be permanently removed.</template
      >
      <template #action>
        <OutlineButton
          @click="modalAlert = false"
          text="Cancel"
          class="border-gray-400 text-gray-600"
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
