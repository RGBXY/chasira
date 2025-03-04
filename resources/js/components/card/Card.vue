<script setup>
import formatPrice from "../../../core/helper/formatPrice";

const props = defineProps({
    product: Object,
});
</script>

<template>
    <div
        class="w-[270px] flex flex-col gap-2.5 p-3 rounded-xl shadow bg-white relative border cursor-pointer overflow-hidden"
    >
        <div
            class="rounded-xl overflow-hidden w-full h-[200px] flex-shrink-0 border relative"
        >
            <div
                class="ps-4 border-b border-l pe-2 py-1 rounded-bl-full absolute right-0 flex items-center bg-gray-100"
            >
                <p class="text-xs text-gray-500 uppercase font-semibold">
                    {{ product.category.name }}
                </p>
            </div>

            <img
                class="object-cover w-full h-full"
                :src="'storage/' + product.image"
                alt="Product Image"
            />
        </div>

        <div class="flex flex-col gap-1">
            <div class="flex-grow">
                <h1 class="font-semibold text-lg text-slate-600 line-clamp-1">
                    {{ product.name }}
                </h1>
            </div>

            <div class="flex justify-between items-center">
                <h1 class="font-bold text-lg text-slate-800">
                    {{ formatPrice(product.sell_price) }}
                </h1>

                <h1
                    :class="
                        product.stock > 0 ? 'text-gray-600' : 'text-red-600'
                    "
                    class="font-semibold"
                >
                    {{
                        product.stock > 0
                            ? product.stock + " Items"
                            : "Out of stock"
                    }}
                </h1>
            </div>
        </div>
    </div>
</template>
