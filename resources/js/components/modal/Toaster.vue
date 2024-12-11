<template>
    <div
        :class="method.toasterStat ? 'right-5' : '-right-1/2'"
        class="fixed bottom-5 z-40 bg-white transition-all delay-200 duration-300 ease-in-out border border-green-500 border-l-[6px] rounded-lg shadow-lg w-[350px] max-h-[400px] overflow-y-auto"
    >
        <div class="w-full h-full relative">
            <div
                class="h-12 border-b flex sticky top-0 bg-white border-gray-300 items-center justify-between px-4"
            >
                <h1 class="text-xl font-bold text-green-500">Success!</h1>
                <button
                    @click="method.toasterFncClose"
                    class="text-lg text-black border-2 border-violet-300 p-1 rounded-full"
                >
                    <PhX class="" weight="bold" />
                </button>
            </div>
            <div class="p-4 text-gray-900 font-semibold">
                <p>{{ message }}</p>
            </div>

            <div
                class="w-full h-1 bg-gradient-to-b from-green-100 to bg-green-500 bar absolute bottom-0"
            ></div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { useMethodStore } from "../../stores/method";
import { PhX } from "@phosphor-icons/vue";
import { onMounted, ref, watch, watchEffect } from "vue";

const method = useMethodStore();
const page = usePage();
const message = ref(page.props.flash.message);

watch(
    () => message.value,
    (newValue) => {
        if (newValue !== null) {
            method.toasterFnc();
        } else {
            method.toasterFncClose();
        }
    },
    { immediate: true }
);

onMounted(() => {
    watchEffect(() => {
        if (message.value) {
            const intervalId = setInterval(() => {
                method.toasterFncClose();

                if (!method.toasterStat) {
                    clearInterval(intervalId);
                }
            }, 5000);
        } else {
            method.toasterFncClose();
        }
    });
});
</script>

<style>
.bar {
    animation: toaster linear;
    animation-duration: 5s;
    animation-fill-mode: forwards;
}

@keyframes toaster {
    from {
        width: 0px;
    }
    to {
        width: 100%;
    }
}
</style>
