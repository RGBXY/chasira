<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Report Sales</h1>

                    <form
                        @submit.prevent="filter"
                        class="flex gap-3 justify-between"
                    >
                        <div
                            class="w-[300px] h-12 border flex items-center pe-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <label
                                for="start-date"
                                class="text-sm bg-violet-400 h-full px-4 text-white flex items-center"
                            >
                                Starts
                            </label>
                            <input
                                type="date"
                                id="start-date"
                                required
                                v-model="start_date"
                                placeholder="Search Category..."
                                class="h-full outline-none w-full"
                            />
                        </div>
                        <div
                            class="w-[300px] h-12 border flex items-center pe-3 gap-3 bg-white rounded-md overflow-hidden"
                        >
                            <label
                                for="end-date"
                                class="text-sm bg-violet-400 h-full px-4 text-white flex items-center"
                            >
                                End
                            </label>
                            <input
                                id="end-date"
                                type="date"
                                required
                                v-model="end_date"
                                placeholder="Search Category..."
                                class="h-full outline-none w-full"
                            />
                        </div>

                        <button
                            type="submit"
                            class="flex items-center h-12 justify-center px-4 gap-2 bg-violet-400 text-white rounded-md text-sm"
                        >
                            <PhFunnel weight="bold" />
                            Filter
                        </button>
                    </form>
                </div>

                <div class="w-full">
                    <DataTable
                        v-if="props.sales.data.length > 0"
                        :data="props.sales.data"
                        :header="headerConfig"
                    >
                        <template v-slot:date="{ row: i }">
                            <div class="uppercase inline-block rounded-md">
                                {{ formatDate(i.created_at) }}
                            </div>
                        </template>
                        <template v-slot:customers="{ row: i }">
                            <div class="uppercase inline-block rounded-md">
                                {{ i.customers?.name }}
                            </div>
                        </template>
                        <template v-slot:cashier="{ row: i }">
                            <div class="uppercase inline-block rounded-md">
                                {{ i.cashier.name }}
                            </div>
                        </template>
                        <template v-slot:total="{ row: i }">
                            <div class="uppercase inline-block rounded-md">
                                {{ formatPrice(i.total) }}
                            </div>
                        </template>
                        <template v-slot:grand_total="{ row: i }">
                            <div class="uppercase inline-block rounded-md">
                                {{ formatPrice(i.grand_total) }}
                            </div>
                        </template>

                        <template v-slot:actions="{ row: i }">
                            <div class="flex items-start gap-2">
                                <button
                                    @click="modalButtonFnc(i.id)"
                                    class="border-gray-300 border px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-primary-bg font-semibold text-gray-500 text-sm rounded-md"
                                >
                                    <PhEye weight="bold" class="text-base" />
                                    <p>Detail</p>
                                </button>
                            </div>
                        </template>
                    </DataTable>

                    <NoData
                        v-else
                        header="No Data Sales Found"
                        sub="Start by making a transaction on the cashier page. After that, you can see your data"
                        button="Make a Transaction"
                        link="/"
                    />

                    <ModalSalesDetail>
                        <div class="">
                            <div class="p-5 flex flex-col gap-4">
                                <ContentDetail
                                    title="Invoice"
                                    :value="saleDetail.invoice"
                                />

                                <ContentDetail
                                    title="Date"
                                    :value="formatDate(saleDetail.created_at)"
                                />

                                <ContentDetail
                                    title="Total"
                                    :value="formatPrice(saleDetail.total)"
                                />

                                <ContentDetail
                                    title="Discount"
                                    :value="formatPrice(saleDetail.discount)"
                                />

                                <ContentDetail
                                    title="Grand Total"
                                    :value="formatPrice(saleDetail.grand_total)"
                                />

                                <ContentDetail
                                    title="Customer"
                                    :value="saleDetail.customers?.name"
                                />

                                <ContentDetail
                                    title="Cashier"
                                    :value="saleDetail.cashier?.name"
                                />

                                <ContentDetail
                                    title="Cashier"
                                    :value="saleDetail.cashier?.name"
                                />

                                <ContentDetail
                                    title="Cash"
                                    :value="formatPrice(saleDetail.cash)"
                                />

                                <ContentDetail
                                    title="Change"
                                    :value="formatPrice(saleDetail.change)"
                                />
                            </div>

                            <div class="border-t flex flex-col gap-4 p-5">
                                <p class="font-medium text-gray-500">
                                    Products
                                </p>

                                <div class="overflow-x-auto">
                                    <DataTable
                                        :header="headerConfigModal"
                                        :data="transactionDetail"
                                    >
                                        <template v-slot:products="{ row: i }">
                                            <div
                                                class="uppercase inline-block rounded-md"
                                            >
                                                {{ i.product.name }}
                                            </div>
                                        </template>
                                        <template v-slot:price="{ row: i }">
                                            <div
                                                class="uppercase inline-block rounded-md"
                                            >
                                                {{
                                                    formatPrice(
                                                        i.product.buy_price
                                                    )
                                                }}
                                            </div>
                                        </template>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                    </ModalSalesDetail>

                    <Pagination :pagination="props.sales" />
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { PhEye, PhFunnel } from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import { router } from "@inertiajs/vue3";
import formatPrice from "../../../core/helper/formatPrice";
import { computed, ref } from "vue";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";
import DataTable from "../../components/layouts/DataTable.vue";
import ModalSalesDetail from "../../components/modal/ModalSalesDetail.vue";
import { useMethodStore } from "../../stores/method";
import ContentDetail from "../../components/ui/ContentDetail.vue";

const method = useMethodStore();

const props = defineProps({
    sales: Object,
    total: String,
    transaction_detail: Object,
});

const headerConfig = [
    { key: "invoice", label: "Invoice" },
    { key: "date", label: "Date" },
    { key: "customers", label: "Customers" },
    { key: "cashier", label: "Cashier" },
    { key: "total", label: "Total" },
    { key: "discount", label: "Discount" },
    { key: "grand_total", label: "Grand Total" },
];

const headerConfigModal = [
    { key: "products", label: "Products" },
    { key: "price", label: "Price" },
    { key: "qty", label: "Qty" },
];

const saleDetail = ref("");
const transactionDetail = ref("");

const start_date = ref(
    "" || new URL(document.location).searchParams.get("start_date")
);

const end_date = ref(
    "" || new URL(document.location).searchParams.get("end_date")
);

//define method search
const filter = () => {
    //HTTP request
    router.get("/sales/filter", {
        //send data to server
        start_date: start_date.value,
        end_date: end_date.value,
    });
};

const formatDate = (date) => {
    if (!date) return "N/A";

    const options = {
        day: "2-digit", // Tanggal
        month: "2-digit", // Bulan
        year: "numeric", // Tahun
        hour: "2-digit", // Jam
        minute: "2-digit", // Menit
        hourCycle: "h23", // Format 24-jam
    };

    return new Intl.DateTimeFormat("en-US", options).format(new Date(date));
};

const modalButtonFnc = (id) => {
    method.modalDetailFnc();

    const sale = props.sales.data;
    const transaction = props.transaction_detail;

    const saleFiltered = sale.find((data) => data.id === id);

    const transactionFiltered = transaction.filter(
        (data) => data.transaction_id === id
    );

    saleDetail.value = saleFiltered;

    transactionDetail.value = transactionFiltered;
};
</script>
