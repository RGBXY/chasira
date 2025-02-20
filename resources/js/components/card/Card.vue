<template>
    <div
        v-for="product in products"
        :key="product.id"
        @click="addOrder(product.id)"
        class="w-[300px] p-3 rounded-xl bg-white relative border cursor-pointer overflow-hidden"
    >
        <div
            :class="check?.id === product.id ? 'block' : 'hidden'"
            class="absolute w-full h-full bg-gray-400 left-0 top-0 z-30 bg-opacity-80 flex items-center justify-center"
        >
            <p
                class="flex items-center text-center justify-center mb-2 text-white text-3xl font-bold"
            >
                Out of Stock
            </p>
        </div>

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
import { computed, ref } from "vue";
import formatPrice from "../../../core/helper/formatPrice";
import { useReceiptStore } from "../../stores/receipt";

const receiptStore = useReceiptStore();
const check = ref(null);

const props = defineProps({
    products: Array,    
});

const addOrder = (id) => {
    const newOrder = props.products.find((item) => item.id === id);

    if (!newOrder) return; // Tambahkan pengecekan agar aman

    const perProduct = receiptStore.products.find((item) => item.id === id);
    const stock = newOrder.stock;

    if (!perProduct && stock !== 0) {
        newOrder.total = 1;
        receiptStore.products.push(newOrder);
    } else if (perProduct && perProduct.total < perProduct.stock) {
        perProduct.total++;
    }
};
</script>
