<template>
    <div
        class="w-full flex flex-col gap-2 items-center justify-center h-[500px]"
    >
        <h1 class="text-2xl font-bold">{{ props.header }}</h1>
        <p class="w-[35%] text-center mb-2">
            {{ props.sub }}
        </p>
        <Link :href="props.link">
            <PrimButtonModal :text="props.button" />
        </Link>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import PrimButtonModal from "../ui/primButtonModal.vue";

const props = defineProps({
    header: String,
    sub: String,
    button: String,
    link: String,
});
</script>
