<template>
    <div class="w-[25%] border-l bg-white right-0 z-30">
        <div
            class="fixed top-0 right-0 overflow-y-auto flex flex-col justify-between w-[25%] h-full"
        >
            <div class="h-[80%]">
                <div
                    class="h-20 px-3 border-b flex items-center justify-between"
                >
                    <div class="flex items-center gap-1.5">
                        <PhReceipt class="text-2xl" />
                        <h1 class="text-xl font-bold">List Order</h1>
                    </div>
                    <button
                        @click="clearData"
                        class="h-9 border-[1.5px] hover:bg-gray-100 text-gray-400 border-gray-400 px-3 rounded-lg text-sm font-semibold"
                    >
                        Clear Order
                    </button>
                </div>

                <div class="h-[80%] w-full overflow-y-auto">
                    <div
                        v-if="receiptStore.products.length < 1"
                        class="flex items-center justify-center h-full"
                    >
                        <h1 class="text-2xl font-semibold">No order</h1>
                    </div>

                    <div v-else class="divide-y-2 divide-dashed">
                        <div
                            v-for="product in receiptStore.products"
                            :key="product.id"
                            class="p-3 flex gap-2.5"
                        >
                            <div class="w-[150px] h-[80px]">
                                <img
                                    class="w-full h-full object-cover rounded-xl"
                                    :src="'storage/' + product.image"
                                    alt=""
                                />
                            </div>
                            <div class="w-full">
                                <h1 class="font-semibold">
                                    {{ product.name }}
                                </h1>
                                <p class="text-sm mb-2 text-gray-500">
                                    {{ formatPrice(product.sell_price) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <button
                                        @click="removeOrder(product.id)"
                                        class="h-7 w-7 flex hover:bg-gray-100 items-center justify-center rounded-full border-2"
                                    >
                                        <PhTrash />
                                    </button>
                                    <div
                                        class="flex justify-between gap-3 bg-gray-100 py-1 px-1 min-w-[110px] rounded-full"
                                    >
                                        <button
                                            @click="decrement(product.id)"
                                            class="bg-white rounded-full flex items-center justify-center h-[25px] w-[25px]"
                                        >
                                            <PhMinus />
                                        </button>
                                        <p>{{ product.total }}</p>
                                        <button
                                            @click="increment(product.id)"
                                            class="bg-white rounded-full flex items-center justify-center h-[25px] w-[25px]"
                                        >
                                            <PhPlus />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="min-h-[10%] border-t p-3">
                    <div class="flex items-center mb-1.5 justify-between">
                        <p class="">Subtotal</p>
                        <p>{{ formatPrice(total) }}</p>
                    </div>
                    <div class="flex items-center mb-1.5 justify-between">
                        <p class="">Tax</p>
                        <p>Rp 0</p>
                    </div>
                    <div class="flex items-center mb-1.5 justify-between">
                        <p class="">Discount</p>
                        <p>- Rp 0</p>
                    </div>
                    <div
                        class="flex items-center font-semibold border-t-2 border-dashed justify-between h-12"
                    >
                        <p class="text-xl">TOTAL</p>
                        <p>{{ formatPrice(total) }}</p>
                    </div>
                </div>
            </div>

            <button
                :disabled="receiptStore.products.length < 1 || form.processing"
                @click="submit"
                :class="
                    receiptStore.products.length < 1
                        ? 'bg-violet-300'
                        : 'bg-violet-400 '
                "
                class="font-semibold text-xl text-white w-full h-[8%] border-t"
            >
                Place Order
            </button>
        </div>
    </div>

    <ModalPayment :total="total" />

    <ModalPrint />
</template>

<script setup>
import { PhMinus, PhPlus, PhReceipt, PhTrash } from "@phosphor-icons/vue";
import { useReceiptStore } from "../../stores/receipt.js";
import formatPrice from "../../../core/helper/formatPrice.js";
import { ref, watch } from "vue";
import { useMethodStore } from "../../stores/method.js";
import ModalPayment from "../modal/ModalPayment.vue";
import ModalPrint from "../modal/ModalPrint.vue";
import { useForm } from "@inertiajs/vue3";

const receiptStore = useReceiptStore();
const method = useMethodStore();

const total = ref(null);

const form = useForm({
    products: [],
});

const submit = async () => {
    form.products = receiptStore.products;
    form.post("/transactions/addToCart", {
        onSuccess: () => {
            method.modalPaymentFnc();
        },
    });
};

const clearData = () => {
    receiptStore.products.forEach((item) => {
        item.total = 1;
    });
    receiptStore.products = [];
};

const removeOrder = (id) => {
    receiptStore.products.forEach((item) => {
        if (item.id === id) {
            item.total = 1;
        }
    });

    const product = receiptStore.products.filter((item) => item.id !== id);
    receiptStore.products = product;
};

const increment = (id) => {
    const product = receiptStore.products.find((item) => item.id === id);
    if (product.stock > product.total) {
        product.total++;
    }
};

const decrement = (id) => {
    const product = receiptStore.products.find((item) => item.id === id);
    if (product.total < 2) {
        receiptStore.products = receiptStore.products.filter(
            (item) => item.id !== id
        );
    } else {
        product.total--;
    }
};

const totalPrice = () => {
    total.value = receiptStore.products.reduce(
        (acc, item) => acc + item.sell_price * item.total,
        0
    );
};

watch(
    () => receiptStore.products, // Data yang ingin dipantau
    (newProducts, oldProducts) => {
        // Memanggil totalPrice jika ada perubahan
        totalPrice();
    },
    { deep: true } // Supaya mendeteksi perubahan elemen dalam array
);
</script>

<style lang="scss" scoped></style>
