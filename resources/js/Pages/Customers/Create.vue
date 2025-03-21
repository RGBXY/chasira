<template>
  <Layout>
    <div class="w-full py-8 px-7 flex items-center justify-center">
      <form
        @submit.prevent="submit"
        class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg"
      >
        <div class="mb-6 flex items-center justify-between">
          <h1 class="text-3xl font-bold mb-1">Add Customer</h1>
        </div>

        <div class="w-full flex flex-col gap-3">
          <TextInput
            name="Customer Name"
            type="text"
            v-model="form.name"
            placeholder="Your Customer name"
            :message="form.errors.name"
          />

          <TextInput
            name="Customer Address"
            type="text"
            v-model="form.address"
            placeholder="Your Customer Address"
            :message="form.errors.address"
          />

          <TextInput
            name="Customer Phone"
            type="number"
            v-model="form.phone"
            placeholder="Your Customer Phone"
            :message="form.errors.phone"
          />

          <div class="mb-2">
            <label for="" class="mb-1 text-[13px]">Gender</label>
            <div
              :class="form.errors.gender ? 'border-red-500' : 'border-gray-300'"
              class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
            >
              <select
                v-model="form.gender"
                name=""
                id=""
                class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
              >
                <option value="" disabled selected>Your Customer Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
            <small v-if="form.errors.gender" class="text-red-500">{{
              form.errors.gender
            }}</small>
          </div>

          <TextAreaInput
            name="Description"
            rows="4"
            v-model="form.description"
            placeholder="Your Customer Description"
            :message="form.errors.description"
          />

          <div class="flex justify-end gap-3">
            <Link
              href="/categories"
              type="button"
              class="h-10 px-3 bg-violet-100 rounded-lg flex items-center font-semibold text-violet-400"
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
              <p v-if="form.processing">Loading...</p>
              <p v-else>Submit</p>
            </button>
          </div>
        </div>
      </form>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import TextInput from '../../components/ui/TextInput.vue';
import TextAreaInput from '../../components/ui/TextAreaInput.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  address: '',
  phone: '',
  gender: '',
  description: '',
});

const submit = () => {
  form.post('/customers');
};
</script>

<style lang="scss" scoped></style>
