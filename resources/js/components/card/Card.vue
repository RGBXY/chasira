<template>
    <div
        v-for="product in data"
        :key="product.id"
        @click="addOrder(product.id)"
        class="w-[30%] min-h-[270px] bg-white p-3 rounded-xl relative border cursor-pointer overflow-hidden"
    >
        <!-- <div
            :class="product.stock === 0 ? 'block' : 'hidden'"
            class="absolute w-full h-full bg-gray-400 left-0 top-0 z-30 bg-opacity-80 flex items-center justify-center"
        >
            <p
                class="flex items-center text-center justify-center mb-2 text-white text-3xl font-bold"
            >
                Out of Stock
            </p>
        </div> -->

        <div
            class="h-[200px] w-full rounded-xl overflow-hidden border relative"
        >
            <div
                class="ps-4 border-b border-l pe-2 py-1 rounded-bl-full absolute right-0 flex items-center bg-gray-100"
            >
                <p class="text-xs text-gray-500 uppercase font-semibold">
                    {{ product.category.name }}
                </p>
            </div>
            <img
                class="w-full h-full object-cover"
                :src="'storage/' + product.image"
                alt=""
            />
        </div>
        <div class="mt-3">
            <div class="flex items-center justify-between">
                <h1 class="text-lg leading-6 font-bold text-slate-900">
                    {{ product.name }}
                </h1>
            </div>

            <div class="flex mt-2 justify-between items-center">
                <h1 class="text-gray-500 font-semibold">
                    {{ formatPrice(product.sell_price) }}
                </h1>

                <h1
                    v-if="product.stock === 0"
                    class="text-red-500 font-semibold"
                >
                    Out of Stock
                </h1>
                <h1 v-else class="text-gray-500 font-semibold">
                    {{ product.stock }} items
                </h1>
            </div>
        </div>
    </div>
</template>

<script setup>
import formatPrice from "../../../core/helper/formatPrice";
import { useReceiptStore } from "../../stores/receipt";

const receiptStore = useReceiptStore();

const props = defineProps({
    data: Object,
});

const addOrder = (id) => {
    const newOrder = props.data.find((item) => item.id === id);

    newOrder.total = 1;

    const check = receiptStore.products.find((item) => item.id === id);

    const qty = newOrder.stock;

    if (!check && qty !== 0) {
        receiptStore.products.push(newOrder);
    }
};
</script>
