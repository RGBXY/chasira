<template>
    <Layout>
        <div class="py-8 px-7 flex items-center justify-center">
            <div class="px-10 py-8 w-full max-w-7xl border bg-white rounded-lg">
                <div class="mb-7 flex items-center justify-between pb-4">
                    <h1 class="text-3xl font-bold mb-1">Report Profits</h1>

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
                    <table
                        v-if="props.profits.data.length > 0"
                        class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                    >
                        <thead>
                            <tr class="">
                                <th class="text-start p-3">Date</th>
                                <th class="text-start p-3">Invoice</th>
                                <th class="text-start p-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="profit in props.profits.data">
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="px-2.5 py-1.5 uppercase inline-block rounded-md"
                                    >
                                        {{ formatDate(profit.created_at) }}
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="px-2.5 py-1.5 uppercase inline-block rounded-md"
                                    >
                                        {{ profit.transaction.invoice }}
                                    </div>
                                </td>
                                <td class="border-2 p-3 border-gray-200">
                                    <div
                                        class="px-2.5 py-1.5 uppercase inline-block rounded-md"
                                    >
                                        {{ formatPrice(profit.total) }}
                                    </div>
                                </td>
                            </tr>
                            <tr class="bg-gray-100">
                                <td
                                    colspan="2"
                                    class="border-2 p-3 border-gray-200 text-end"
                                >
                                    <div
                                        class="px-2.5 py-1.5 font-semibold text-end uppercase inline-block rounded-md"
                                    >
                                        Total Sum :
                                    </div>
                                </td>
                                <td
                                    class="border-2 p-3 border-gray-200 text-end"
                                >
                                    <div
                                        class="px-2.5 py-1.5 font-semibold uppercase inline-block rounded-md"
                                    >
                                        {{ formatPrice(props.total) }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <NoData
                        v-else
                        header="No Data Profits Found"
                        sub="Start by making a transaction on the cashier page. After that, you can see your data"
                        button="Make a Transaction"
                        link="/"
                    />

                    <Pagination :pagination="props.profits" />
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { PhFunnel } from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import { router } from "@inertiajs/vue3";
import formatPrice from "../../../core/helper/formatPrice";
import { ref } from "vue";
import Pagination from "../../components/ui/Pagination.vue";
import NoData from "../../components/card/NoData.vue";

const start_date = ref(
    "" || new URL(document.location).searchParams.get("start_date")
);

const end_date = ref(
    "" || new URL(document.location).searchParams.get("end_date")
);

//define method search
const filter = () => {
    //HTTP request
    router.get("/profits/filter", {
        //send data to server
        start_date: start_date.value,
        end_date: end_date.value,
    });
};

const formatDate = (date) => {
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

const props = defineProps({
    profits: Array,
    total: Number,
});
</script>

<style lang="scss" scoped></style>
