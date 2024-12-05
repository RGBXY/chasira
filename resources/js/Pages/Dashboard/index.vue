<template>
    <Layout>
        <div class="flex items-center justify-center py-8 px-9 w-full h-full">
            <div class="h-full w-full max-w-7xl">
                <div class="mb-8 flex flex-col justify-center">
                    <h1 class="text-2xl font-bold mb-1">Dashboard</h1>
                    <p class="text-gray-600 font-medium">
                        Check the sales, value and bounce rate by country.
                    </p>
                </div>

                <div class="flex items-center gap-5 w-full h-full mb-7">
                    <div
                        class="p-5 rounded-xl flex-1 min-h-[120px] bg-white border border-gray-300"
                    >
                        <div
                            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
                        >
                            <PhCoins class="text-3xl" />
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold mb-1">
                                {{ formatPrice(total_sales) }}
                            </h1>
                            <p class="text-gray-500">Total Sales Today</p>
                        </div>
                    </div>
                    <div
                        class="p-5 rounded-xl flex-1 min-h-[120px] bg-white border border-gray-300"
                    >
                        <div
                            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
                        >
                            <PhHandCoins class="text-3xl" />
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold mb-1">
                                {{ formatPrice(profit) }}
                            </h1>
                            <p class="text-gray-500">Total Profits Today</p>
                        </div>
                    </div>
                    <div
                        class="p-5 rounded-xl flex-1 min-h-[120px] bg-white border border-gray-300"
                    >
                        <div
                            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
                        >
                            <PhUsers class="text-3xl" />
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold">
                                {{ total_employees }}
                            </h1>
                            <p class="text-gray-500 mb-1">Total Employees</p>
                        </div>
                    </div>
                    <div
                        class="p-5 rounded-xl flex-1 min-h-[120px] bg-white border border-gray-300"
                    >
                        <div
                            class="bg-violet-500 mb-4 text-white flex items-center justify-center w-[60px] h-[60px] shadow-md shadow-violet-200 rounded-xl"
                        >
                            <PhStorefront class="text-3xl" />
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold">
                                {{ total_outlets }}
                            </h1>
                            <p class="text-gray-500 mb-1">Total Outlets</p>
                        </div>
                    </div>
                </div>

                <div class="w-full flex gap-5">
                    <div
                        class="flex-1 bg-white rounded-xl border-gray-300 border p-5"
                    >
                        <h1 class="text-xl font-semibold">Total Sales Week</h1>
                        <p class="text-gray-500">Total Sales This Last Week</p>
                        <LineChart
                            :chartData="sales"
                            :options="options"
                            class="h-[200px] mt-5"
                        />
                    </div>
                    <div
                        class="flex-1 bg-white rounded-xl border border-gray-300 p-5"
                    >
                        <h1 class="text-xl font-semibold">
                            Total Profits Week
                        </h1>
                        <p class="text-gray-500">
                            Total Profits This Last Week
                        </p>
                        <LineChart
                            :chartData="profits"
                            :options="options"
                            class="h-[200px] mt-5"
                        />
                    </div>
                </div>

                <div class="py-8 flex w-full items-center justify-center">
                    <div
                        class="px-10 py-8 w-full border border-gray-300 bg-white rounded-lg"
                    >
                        <div
                            class="mb-3 flex items-center justify-between pb-4"
                        >
                            <h1 class="text-3xl font-bold mb-1">
                                Best Seller Products
                            </h1>
                        </div>

                        <div class="w-full">
                            <table
                                class="table-auto w-full rounded-lg border-2 border-gray-200 overflow-hidden"
                            >
                                <thead>
                                    <tr class="">
                                        <th class="text-start p-3">No</th>
                                        <th class="text-start p-3">Name</th>
                                        <th class="text-start p-3">Category</th>
                                        <th class="text-start p-3">
                                            Buy Price
                                        </th>
                                        <th class="text-start p-3">
                                            Sell Price
                                        </th>
                                        <th class="text-start p-3">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            class="border-2 p-3 border-gray-200"
                                        >
                                            <p>1</p>
                                        </td>
                                        <td
                                            class="border-2 p-3 border-gray-200"
                                        >
                                            <p>Buku Sidu</p>
                                        </td>
                                        <td
                                            class="border-2 p-3 border-gray-200"
                                        >
                                            <p>Books</p>
                                        </td>
                                        <td
                                            class="border-2 p-3 gap-3 border-gray-200"
                                        >
                                            Rp 10.000
                                        </td>
                                        <td
                                            class="border-2 p-3 border-gray-200"
                                        >
                                            Rp 12.000
                                        </td>
                                        <td
                                            class="border-2 p-3 border-gray-200"
                                        >
                                            <div
                                                class="bg-gray-100 px-2.5 py-1.5 uppercase font-semibold inline-block text-gray-500 text-sm rounded-md"
                                            >
                                                <p>40</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="w-full flex gap-5">
                    <div
                        class="flex-1 bg-white rounded-xl border-gray-300 border p-5"
                    >
                        <h1 class="text-xl font-semibold">Best Outlets</h1>
                        <p class="text-gray-500">
                            Best Selling Outlets This Week
                        </p>
                        <BarChart
                            :chartData="outlets"
                            :options="options"
                            class="h-[200px] mt-5"
                        />
                    </div>
                    <div
                        class="flex-1 bg-white rounded-xl border border-gray-300 p-5"
                    >
                        <h1 class="text-xl font-semibold">Best Employees</h1>
                        <p class="text-gray-500">
                            Best Selling Employees This Week
                        </p>
                        <BarChart
                            :chartData="employees"
                            :options="options"
                            class="h-[200px] mt-5"
                        />
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import {
    PhCoins,
    PhHandCoins,
    PhStorefront,
    PhUsers,
} from "@phosphor-icons/vue";
import Layout from "../../Layouts/Layout.vue";
import { BarChart, LineChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";
import formatPrice from "../../../core/helper/formatPrice";
import { ref } from "vue";

const props = defineProps({
    total_sales: Object,
    profit: Object,
    total_employees: Object,
    total_outlets: Object,
});

Chart.register(...registerables);

const options = ref({
    responsive: true,
    plugins: {
        legend: {
            display: false,
        },
        title: {
            display: false,
        },
    },
    beginZero: true,
});

const sales = {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [
        {
            label: ["Sales"],
            data: [3000, 10000, 7000, 20000, 30000],
            backgroundColor: ["#7c3aed"],
        },
    ],
};

const profits = {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [
        {
            label: ["Sales"],
            data: [3000, 10000, 7000, 20000, 30000],
            backgroundColor: ["#7c3aed"],
        },
    ],
};

const outlets = {
    labels: [
        "Oulet1",
        "Outlet2",
        "Outlet3",
        "Outlet4",
        "Outlet5",
        "Outlet6",
        "Outlet7",
    ],
    datasets: [
        {
            label: ["Transaction"],
            data: [80, 60, 47, 30, 20, 15, 10],
            backgroundColor: ["#7c3aed"],
        },
    ],
};

const employees = {
    labels: ["Budi", "Tina", "Addie", "Udin", "Irpan", "Robby", "Abas"],
    datasets: [
        {
            label: ["Transaction"],
            data: [80, 60, 47, 30, 20, 15, 10],
            backgroundColor: ["#7c3aed"],
        },
    ],
};
</script>
