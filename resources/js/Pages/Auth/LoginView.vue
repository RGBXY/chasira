<template>
  <div class="flex justify-center items-center w-full h-screen bg-primary-bg">
    <div
      class="w-[340px] border-[1.5px] border-white shadow-md rounded-2xl bg-gradient-to-b from-primary-bg to-30% to-white"
    >
      <div
        class="border-b-gray-300 flex flex-col justify-center items-center pt-8"
      >
        <img
          class="w-12 rounded-xl mb-4 shadow-sm border-[1.5px]"
          :src="'/assets/image/logo.png'"
          alt="hai"
        />
        <h1 class="text-xl font-semibold">Welcome back</h1>
        <p class="text-sm">Please enter your details to sign in</p>
      </div>

      <form @submit.prevent="submit" class="p-5">
        <TextInput
          name="Email"
          v-model="form.email"
          type="email"
          :message="form.errors.email"
          placeholder="Enter your email"
        />

        <TextInput
          name="Password"
          v-model="form.password"
          :message="form.errors.password"
          type="password"
          placeholder="Enter your password"
        />

        <div class="flex items-center justify-between mt-3 mb-5">
          <div class="flex items-center gap-1.5">
            <input type="checkbox" v-model="form.remember" />
            <label class="text-xs font-semibold">Remember me</label>
          </div>

          <Link>
            <p class="text-xs text-violet-700 font-semibold underline">
              Forgot Password?
            </p>
          </Link>
        </div>

        <button
          :disabled="form.processing"
          :class="form.processing ? 'text-gray-200' : 'text-white '"
          class="w-full mb-3 h-10 border bg-gradient-to-b from-violet-400 border-violet-700 border-b-2 to-violet-600 text-sm font-semibold rounded-lg"
          type="submit"
        >
          <p v-if="form.processing">Loading...</p>
          <p v-else>Sign in</p>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import TextInput from '../../components/ui/TextInput.vue';

const form = useForm({
  email: '',
  password: '',
  remember: '',
});

const submit = () => {
  form.post('/login');
};
</script>
