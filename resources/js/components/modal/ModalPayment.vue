<template>
    <div
        :class="
            method.modalPyamentStat
                ? ' opacity-100 visible'
                : 'opacity-0 invisible'
        "
        class="bg-black backdrop-blur-sm bg-opacity-40 fixed transition-all duration-500 left-0 top-0 w-full h-screen z-50 flex items-end justify-center"
    >
        <div
            :class="method.modalPyamentStat ? ' h-[700px]' : ' h-0'"
            class="w-full bg-white flex relative transition-all overflow-y-auto duration-500 justify-center p-10 rounded-t-[80px]"
        >
            <button
                @click="modalClose"
                class="rounded-full p-1 absolute right-14 border border-black"
            >
                <PhX class="text-2xl" />
            </button>
            <form @submit.prevent="pay()" class="bg-white w-full max-w-xl">
                <div
                    class="h-16 mb-5 flex items-center justify-between border-b"
                >
                    <div class="flex gap-4">
                        <h1 class="text-xl font-semibold">Payment Method</h1>
                    </div>
                    <button
                        type="submit"
                        class="h-9 px-4 font-semibold text-sm border border-violet-400 hover:bg-violet-400 rounded-md text-violet-400 hover:text-white"
                    >
                        Charge
                    </button>
                </div>
                <div class="">
                    <div
                        class="w-full flex flex-col items-center mb-5 justify-center min-h-[140px] rounded-xl bg-gradient-to-b from-violet-400 to-violet-600"
                    >
                        <p
                            class="text-white font-semibold text-xl leading-none mb-1.5"
                        >
                            Total Payment
                        </p>
                        <h1 class="text-4xl font-extrabold text-white">
                            {{ formatPrice(total) }}
                        </h1>
                    </div>

                    <div>
                        <h1 class="text-xl font-bold mb-1.5">Cash :</h1>
                        <div class="flex gap-2 mb-2">
                            <div>
                                <input
                                    type="radio"
                                    id="price1"
                                    name="price"
                                    :value="total"
                                    v-model="cash"
                                    class="peer hidden"
                                    :disabled="cash3 !== null && cash3 !== ''"
                                />
                                <label
                                    for="price1"
                                    class="h-14 peer-checked:bg-violet-500 cursor-pointer peer-checked:text-white text-violet-500 border-violet-500 border px-4 flex items-center rounded-lg"
                                >
                                    <p class="font-semibold text-xl">
                                        {{ formatPrice(total) }}
                                    </p>
                                </label>
                            </div>
                            <div>
                                <input
                                    type="radio"
                                    id="price2"
                                    name="price"
                                    :value="cash2"
                                    class="peer hidden"
                                    :disabled="cash3 !== null && cash3 !== ''"
                                />
                                <label
                                    for="price2"
                                    class="h-14 peer-checked:bg-violet-500 cursor-pointer peer-checked:text-white text-violet-500 border-violet-500 border px-4 flex items-center rounded-lg"
                                >
                                    <p class="font-semibold text-xl">
                                        {{ formatPrice(cash2) }}
                                    </p>
                                </label>
                            </div>
                        </div>
                        <input
                            type="number"
                            v-model="cash3"
                            name=""
                            @input="cashInput"
                            id=""
                            placeholder="Cash Amount"
                            class="w-full focus:border-2 border-violet-500 text-sm h-11 px-2 border rounded-md outline-none"
                        />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useMethodStore } from "../../stores/method";
import formatPrice from "../../../core/helper/formatPrice";
import { onMounted, ref, watch } from "vue";
import { PhX } from "@phosphor-icons/vue";
import { useReceiptStore } from "../../stores/receipt";
import { router, useForm } from "@inertiajs/vue3";

const method = useMethodStore();
const receipt = useReceiptStore();

const cash = ref(null);
const cash2 = ref(0);
const cash3 = ref(null);
const discount = ref(0);
const notEnoughCash = ref(false);

const props = defineProps({
    total: Number,
});

const modalClose = () => {
    router.post("/transactions/destroyCart");
    method.modalPaymentFnc();
};

const pay = () => {
    const form = useForm({
        cash: cash3.value,
        change: cash3.value - props.total,
        discount: discount.value,
        grand_total: cash3.value - props.total - discount.value,
    });

    if (cash3.value > props.total) {
        form.post("/");
        receipt.change = cash3.value - props.total;
        method.modalPrintFnc(cash3.value);
        method.modalPaymentFnc();
    } else {
        notEnoughCash.value = true;
    }
};

const cashInput = () => {
    const price1Input = document.getElementById("price1");
    const price2Input = document.getElementById("price2");

    if (cash3.value !== null) {
        price1Input.checked = false;
        price2Input.checked = false;
    }
};

const cashPrice = () => {
    if (props.total > 50000) {
        cash2.value = 100000;
    } else if (props.total > 20000) {
        cash2.value = 50000;
    } else if (props.total > 10000) {
        cash2.value = 20000;
    } else {
        cash2.value = 10000;
    }
};

watch(
    () => props.total,
    () => {
        cashPrice();
    }
);

watch(
    () => cash3.value,
    () => {
        cashInput();
    }
);

onMounted(() => {
    const price1Input = document.getElementById("price1");
    const price2Input = document.getElementById("price2");
    price1Input.checked = false;
    price2Input.checked = false;
});
</script>

<style lang="scss" scoped></style>
