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
                <h1 class="text-xl font-semibold">Welcome to Cashira</h1>
                <p class="text-[11px]">Let's Create your account</p>
            </div>

            <form @submit.prevent="submit" class="p-5">
                <TextInput
                    name="Name"
                    v-model="form.name"
                    :message="form.errors.name"
                />

                <TextInput
                    name="Email"
                    v-model="form.email"
                    type="email"
                    :message="form.errors.email"
                />

                <TextInput
                    name="Password"
                    v-model="form.password"
                    :message="form.errors.password"
                    type="password"
                />

                <TextInput
                    name="Password Confirmation"
                    v-model="form.password_confirm"
                    type="password"
                />

                <button
                    :disabled="form.processing"
                    :class="form.processing ? 'text-gray-200' : 'text-white '"
                    class="w-full mb-3 mt-5 h-10 border bg-gradient-to-b from-violet-400 border-violet-700 border-b-2 to-violet-600 text-sm font-semibold rounded-lg"
                    type="submit"
                >
                    <p v-if="form.processing">Loading...</p>
                    <p v-else>Sign up</p>
                </button>

                <p class="text-xs text-center">
                    Already have an account?
                    <Link href="/login" class="text-violet-700">Sign in</Link>
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "../../components/ui/TextInput.vue";
// import { ref } from "vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirm: "",
});

const submit = () => {
    form.post("/register");
};
</script>
