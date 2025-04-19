<template>
  <div class="w-full py-8 px-7 flex items-center justify-center">
    <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold mb-1">Add Employee</h1>
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

        <DropdownInput
          label="Role Name"
          v-model="name"
          :items="rolesData"
          :loading="loading"
          placeholder="Role Name"
          @keydown="RoleDropdown"
          @select="selectCategories"
          :message="form.errors.role_id"
        >
        </DropdownInput>

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
</template>

<script setup>
import { debounce } from 'lodash';
import Layout from '../../Layouts/Layout.vue';
import DropdownInput from '../../components/ui/DropdownInput.vue';
import TextInput from '../../components/ui/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
  layout: Layout,
});

const props = defineProps({
  roles: Object,
});

const form = useForm({
  name: '',
  email: '',
  password: '',
  role_id: '',
  status: 'active',
});

const rolesData = ref(props.roles);
const loading = ref(false);
const selectedRole = ref(null);
const name = ref('');

const submit = () => {
  form.post('/employees');
};

const RoleDropdown = debounce(() => {
  if (!name.value) {
    rolesData.value = props.roles;
    return;
  }

  loading.value = true;

  axios
    .post('/roles/dropDownRole', { name: name.value })
    .then((response) => {
      if (response.data.success && response.data.data.length > 0) {
        rolesData.value = response.data.data;
      } else {
        rolesData.value = [];
      }
    })
    .catch((error) => {
      console.error(error);
      rolesData.value = [];
    })
    .finally(() => {
      loading.value = false;
    });
}, 500);

const selectCategories = (role) => {
  selectedRole.value = role;
  name.value = role.name;
  form.role_id = role.id;
};
</script>
