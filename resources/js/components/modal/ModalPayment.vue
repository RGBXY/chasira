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
                        <h1 class="text-xl font-semibold">Payment</h1>
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
                        <div class="mb-5">
                            <h1 class="text-xl font-bold mb-1.5">
                                Customers :
                            </h1>
                            <div
                                class="h-10 rounded-lg bg-white px-2 border-[1.5px]"
                            >
                                <select
                                    v-model="customer"
                                    name=""
                                    id=""
                                    class="h-full w-full bg-transparent text-sm rounded-lg border-none outline-none"
                                >
                                    <option value="" disabled selected>
                                        Customers
                                    </option>
                                    <option
                                        v-for="customer in customers"
                                        :value="customer.id"
                                    >
                                        {{ customer.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <h1 class="text-xl font-bold mb-1.5">Cash :</h1>

                        <input
                            type="number"
                            v-model="cash"
                            name=""
                            id=""
                            placeholder="Cash Amount"
                            class="w-full focus:border-2 border-violet-500 text-sm h-11 px-2 border rounded-md outline-none"
                        />

                        <h1 class="text-xl font-bold mb-1.5">Discount :</h1>

                        <input
                            type="number"
                            v-model="discount"
                            name=""
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
import { ref, watch } from "vue";
import { PhX } from "@phosphor-icons/vue";
import { useReceiptStore } from "../../stores/receipt";
import { useForm } from "@inertiajs/vue3";
import { storeToRefs } from "pinia";

const method = useMethodStore();
const receipt = useReceiptStore();

const cash = ref(null);
const customer = ref("");
const discount = ref(0);
const notEnoughCash = ref(false);

const formGlobal = useForm({});

const { customers } = storeToRefs(receipt);

const props = defineProps({
    total: Number,
});

const modalClose = () => {
    formGlobal.post("/transactions/destroyCart", {
        onSuccess: () => {
            method.modalPaymentFnc();
        },
    });
};

const pay = () => {
    const form = useForm({
        cash: cash.value,
        change: cash.value - props.total,
        discount: discount.value,
        total: props.total,
        customer_id: customer.value,
        grand_total: props.total - discount.value,
    });

    if (cash.value >= props.total && customer.value !== "") {
        form.post("/", {
            onSuccess: () => {
                receipt.change = cash.value - props.total;
                method.modalPrintFnc(cash.value);
                method.modalPaymentFnc();
            },
        });
    } else {
        notEnoughCash.value = true;
    }
};

watch(
    () => receipt.customers,
    (newVal) => {
        if (newVal) {
            customers.value = newVal;
        }
    }
);
</script>
