<template>
    <div
        :class="
            method.modalDetailStat
                ? 'opacity-100 visible'
                : 'opacity-0 invisible '
        "
        @click="method.modalDetailFnc()"
        class="fixed w-full h-screen top-0 left-0 transition-all right-0 p-3 bg-black bg-opacity-15 z-50 flex items-center justify-end"
    >
        <div
            @click.stop
            :class="method.modalDetailStat ? 'right-2.5' : '-right-1/2'"
            class="w-1/3 h-[98%] overflow-y-auto bg-white rounded-xl fixed transition-all border border-gray-400"
        >
            <div
                class="h-20 flex items-center px-5 sticky top-0 bg-white shadow-sm justify-between border-b"
            >
                <div class="mt-1">
                    <h1 class="text-xl font-semibold">Detail Sales</h1>
                    <p class="text-sm text-gray-600">Your detail sales</p>
                </div>
                <button @click="method.modalDetailFnc">
                    <PhX class="text-xl" weight="bold" />
                </button>
            </div>

            <div class="">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { PhX } from "@phosphor-icons/vue";
import { useMethodStore } from "../../stores/method";

const method = useMethodStore();
</script>
