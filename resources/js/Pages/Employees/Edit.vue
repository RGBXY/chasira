<template>
  <Layout>
    <div class="py-8 px-7 flex items-center justify-center">
      <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
        <div class="mb-6 flex items-center justify-between">
          <h1 class="text-3xl font-bold mb-1">Edit Employee</h1>
        </div>

        <form @submit.prevent="submit()" class="w-full flex flex-col gap-3">
          <TextInput
            name="Name"
            type="text"
            v-model="form.name"
            placeholder="Your employee name"
            :message="form.errors.name"
          />

          <TextInput
            name="Email"
            type="email"
            v-model="form.email"
            placeholder="Your employee email"
            :message="form.errors.email"
          />

          <TextInput
            name="Password"
            type="password"
            v-model="form.password"
            placeholder="Your employee password"
            :message="form.errors.password"
          />

          <div class="mb-2">
            <label for="" class="mb-1 text-[13px]">Outlets</label>
            <p class="text-[13px] text-gray-500 mb-1">
              * If you don't have outlets, this section can be left blank
            </p>
            <div
              :class="
                form.errors.outlet_id ? 'border-red-500' : 'border-gray-300'
              "
              class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
            >
              <select
                v-model="form.outlet_id"
                name=""
                id=""
                class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
              >
                <option value="" disabled selected>
                  If you don't have outlets, this section can be left blank
                </option>
                <option v-for="outlet in outlets" :value="outlet.id">
                  {{ outlet.name }}
                </option>
              </select>
            </div>
            <small v-if="form.errors.outlet_id" class="text-red-500">{{
              form.errors.outlet_id
            }}</small>
          </div>

          <div class="mb-2">
            <label for="" class="mb-1 text-[13px]">Roles</label>
            <div
              :class="
                form.errors.role_id ? 'border-red-500' : 'border-gray-300'
              "
              class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
            >
              <select
                v-model="form.role_id"
                name=""
                id=""
                class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
              >
                <option value="" disabled selected>Roles</option>
                <option v-for="role in roles" :value="role.id">
                  {{ role.name }}
                </option>
              </select>
            </div>
            <small v-if="form.errors.role_id" class="text-red-500">{{
              form.errors.role_id
            }}</small>
          </div>

          <div class="flex justify-end gap-3">
            <Link
              href="/employees"
              class="h-10 px-3 bg-violet-100 flex items-center rounded-lg font-semibold text-violet-400"
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

<script setup>
import Layout from '../../Layouts/Layout.vue';
import TextInput from '../../components/ui/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
  roles: Object,
  outlets: Object,
});

console.log(props.user);

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  password: props.user.password,
  outlet_id: props.user.outlet_id,
  role_id: props.user.role_id,
  status: props.user.status,
});

const submit = () => {
  form.put(`/employees/${props.user.id}`);
};
</script>

<style lang="scss" scoped></style>
