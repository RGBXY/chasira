<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold mb-1">Add Discount Transactions</h1>
      </div>

      <form @submit.prevent="submit()" class="w-full flex flex-col gap-3">
        <TextInput
          name="Name"
          type="text"
          v-model="form.name"
          placeholder="Your Discount name"
          :message="form.errors.name"
        />

        <TextInput
          name="Code"
          type="text"
          v-model="form.code"
          placeholder="Your Discount code"
          :message="form.errors.code"
        />

        <div class="flex justify-between gap-3">
          <TextInput
            name="Start Date"
            type="date"
            class="flex-1"
            v-model="form.start_date"
            placeholder="Start Date"
            :message="form.errors.start_date"
          />

          <TextInput
            name="End Date"
            type="date"
            class="flex-1"
            v-model="form.end_date"
            placeholder="End Date"
            :message="form.errors.end_date"
          />
        </div>

        <TextInput
          name="Percent Discount"
          type="number"
          v-model="form.discount"
          placeholder="10 (Only Number)"
          :message="form.errors.discount"
        />

        <TextInput
          name="Minimal Transaction"
          type="number"
          v-model="form.minimal_transaction"
          placeholder="40000"
          :message="form.errors.minimal_transaction"
        />

        <div>
          <h1 class="text-sm">Customer Only?</h1>
          <el-switch
            v-model="form.customer_only"
            class="mb-2"
            active-text="Yes"
            inactive-text="No"
          />
        </div>

        <TextAreaInput
          name="Description"
          v-model="form.description"
          placeholder="Your Discount Description"
          :message="form.errors.Description"
        />

        <div class="flex justify-end gap-3">
          <Link
            href="/discount-transactions"
            class="h-10 px-3 flex items-center bg-violet-100 rounded-lg font-semibold text-violet-400"
          >
            <p>Back</p>
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
import { Link, useForm } from '@inertiajs/vue3';
import TextAreaInput from '../../components/ui/TextAreaInput.vue';

// Layout
defineOptions({
  layout: Layout,
});

// Form
const form = useForm({
  name: '',
  code: '',
  start_date: '',
  end_date: '',
  discount: '',
  minimal_transaction: '',
  customer_only: false,
  description: '',
});

// Function Post
const submit = () => {
  form.post('/discount-transactions');
};
</script>
