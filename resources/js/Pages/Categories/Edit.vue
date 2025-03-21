<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <form
      @submit.prevent="update()"
      class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg"
    >
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold mb-1">Edit Category</h1>
      </div>

      <div class="w-full flex flex-col gap-3">
        <TextInput
          name="Category Name"
          type="text"
          v-model="form.name"
          placeholder="Your Category name"
          :message="form.errors.name"
        />

        <TextAreaInput
          name="Description"
          rows="4"
          v-model="form.description"
          placeholder="Your Category Description"
          :message="form.errors.description"
        />

        <div class="flex justify-end gap-3">
          <Link
            href="/categories"
            class="h-10 px-3 flex items-center bg-violet-100 rounded-lg font-semibold text-violet-400"
          >
            Back
          </Link>
          <button
            type="submit"
            class="h-10 px-3 bg-violet-400 rounded-lg font-semibold text-white"
            :disabled="form.processing"
            :class="
              form.processing
                ? 'cursor-not-allowed bg-violet-300 text-gray-200'
                : ''
            "
          >
            <p v-if="form.processing">Loading...</p>
            <p v-else>Submit</p>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import TextInput from '../../components/ui/TextInput.vue';
import TextAreaInput from '../../components/ui/TextAreaInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

defineOptions({ layout: Layout });

const props = defineProps({
  category: Object,
});

const form = useForm({
  name: props.category.name,
  description: props.category.description,
});

const update = () => {
  form.put(`/categories/${props.category.id}`);
};
</script>

<style lang="scss" scoped></style>
