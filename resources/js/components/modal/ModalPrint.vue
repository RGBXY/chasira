<script setup>
import { useMethodStore } from "../../stores/method";
import formatPrice from "../../../core/helper/formatPrice";
import { useReceiptStore } from "../../stores/receipt";
import { Icon } from "@iconify/vue/dist/iconify.js";

const method = useMethodStore();
const receipt = useReceiptStore();
</script>

<template>
    <div
        :class="
            method.modalPrintStat
                ? 'opacity-100 visible'
                : 'opacity-0 invisible'
        "
        class="bg-black backdrop-blur-sm bg-opacity-40 fixed transition-all duration-500 left-0 top-0 w-full h-screen z-50 flex items-end justify-center"
    >
        <div
            :class="method.modalPrintStat ? ' h-[700px]' : ' h-0'"
            class="w-full bg-white flex relative transition-all overflow-y-auto duration-500 justify-center p-10 rounded-t-[80px]"
        >
            <button
                @click="method.modalPrintFncClose()"
                class="rounded-full p-1 fixed right-14 border border-black"
            >
                <Icon icon="ph:x" class="text-2xl" />
            </button>
            <div class="bg-white w-full h-full max-w-xl">
                <div
                    class="h-16 mb-5 flex items-center justify-between border-b"
                >
                    <div class="flex gap-4">
                        <h1 class="text-xl font-semibold">Payment Receipt</h1>
                    </div>
                </div>

                <div class="flex flex-col justify-center">
                    <div
                        class="w-full flex flex-col items-center mb-10 justify-center min-h-[170px] rounded-xl bg-gradient-to-b from-violet-400 to-violet-600"
                    >
                        <p
                            class="text-white font-semibold text-xl leading-none mb-1.5"
                        >
                            Total Change
                        </p>
                        <h1 class="text-4xl mb-4 font-extrabold text-white">
                            {{ formatPrice(receipt.change) }}
                        </h1>

                        <p
                            class="text-xl text-white font-semibold border-t-2 pt-3"
                        >
                            Out of {{ formatPrice(method.pay) }}
                        </p>
                    </div>

                    <div
                        class="flex flex-col items-center gap-3 justify-center"
                    >
                        <button
                            class="h-14 text-lg w-full px-4 bg-violet-400 rounded- font-semibold text-white"
                        >
                            Print Receipt
                        </button>
                        <button
                            @click="method.modalPrintFncClose()"
                            class="h-14 text-lg w-full px-4 border border-violet-500 font-semibold hover:bg-violet-400 text-violet-500 hover:text-white"
                        >
                            No, Thank You
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
