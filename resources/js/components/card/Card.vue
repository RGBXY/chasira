<template>
    <div
        v-for="product in products"
        :key="product.id"
        @click="addOrder(product.id)"
        class="w-[24%] min-h-[270px] p-3 bg-white rounded-xl border cursor-pointer"
    >
        <div class="h-[170px] w-full rounded-xl overflow-hidden border">
            <img
                class="w-full h-full object-cover"
                :src="product.image"
                alt=""
            />
        </div>
        <div class="mt-3">
            <h1 class="text-lg leading-none font-bold text-slate-900">
                {{ product.name }}
            </h1>
            <div class="flex mt-2 justify-between items-center">
                <h1 class="text-gray-500 font-semibold">
                    {{ formatPrice(product.price) }}
                </h1>

                <div
                    class="px-3 py-1 rounded-full flex items-center bg-gray-100"
                >
                    <p class="text-xs text-gray-500 uppercase font-semibold">
                        {{ product.category }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import products from "../../../core/data/product";
import formatPrice from "../../../core/helper/formatPrice";
import { useReceiptStore } from "../../stores/receipt";

const receiptStore = useReceiptStore();

const addOrder = (id) => {
    const newOrder = products.find((item) => item.id === id);

    const check = receiptStore.products.find((item) => item.id === id);

    if (!check) {
        receiptStore.products.push(newOrder);
    }
};
</script>
