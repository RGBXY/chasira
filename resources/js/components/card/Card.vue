<template>
    <div
        v-for="product in data"
        :key="product.id"
        @click="addOrder(product.id)"
        class="w-[30%] min-h-[270px] p-3 bg-white rounded-xl border cursor-pointer"
    >
        <div class="h-[200px] w-full rounded-xl overflow-hidden border">
            <img
                class="w-full h-full object-cover"
                :src="'storage/' + product.image"
                alt=""
            />
        </div>
        <div class="mt-3">
            <h1 class="text-lg leading-6 font-bold text-slate-900">
                {{ product.name }}
            </h1>
            <div class="flex mt-2 justify-between items-center">
                <h1 class="text-gray-500 font-semibold">
                    {{ formatPrice(product.sell_price) }}
                </h1>

                <div
                    class="px-3 py-1 rounded-full flex items-center bg-gray-100"
                >
                    <p class="text-xs text-gray-500 uppercase font-semibold">
                        {{ product.category.name }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import formatPrice from "../../../core/helper/formatPrice";
import { useReceiptStore } from "../../stores/receipt";

const receiptStore = useReceiptStore();

const props = defineProps({
    data: Object,
});

const addOrder = (id) => {
    const newOrder = props.data.find((item) => item.id === id);

    newOrder.total = 1;

    console.log(newOrder);

    const check = receiptStore.products.find((item) => item.id === id);

    if (!check) {
        receiptStore.products.push(newOrder);
    }
};
</script>
