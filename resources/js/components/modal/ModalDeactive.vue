<template>
    <div
        :class="
            method.modalDeactive ? 'opacity-100 visible' : 'invisible opacity-0'
        "
        class="fixed top-0 left-0 bg-black backdrop-blur-sm bg-opacity-40 transition-all duration-500 z-50 w-full h-screen flex justify-center items-center"
    >
        <div class="w-[500px] bg-white rounded-lg border shadow-lg py-5 px-6">
            <h1 class="text-xl font-bold mb-2">
                <slot name="header"></slot>
            </h1>

            <p class="text-gray-500 text-sm font-semibold mb-4">
                <slot name="description"></slot>
            </p>

            <div class="flex gap-2 justify-end">
                <slot name="action"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useMethodStore } from "../../stores/method";

const method = useMethodStore();
</script>
