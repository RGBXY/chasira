<template>
    <div
        class="h-16 w-full flex sticky top-0 bg-gray-50 items-center px-7 justify-between"
    >
        <div class="flex items-center gap-3">
            <button
                @click="method.sideBarStatFnc()"
                class="bg-white hover:bg-violet-50 border h-12 w-12 flex items-center justify-center rounded-full"
            >
                <PhList class="text-2xl text-violet-500" weight="bold" />
            </button>

            <div
                class="bg-white border h-12 py-2 ps-2 pe-6 gap-3 flex items-center justify-center rounded-full"
            >
                <div class="bg-violet-50 p-2 rounded-full">
                    <PhCalendarDots class="text-lg text-violet-500" />
                </div>

                <h1 class="font-semibold text-sm">{{ formattedDate }}</h1>
            </div>

            <div
                class="bg-white border h-12 py-2 ps-2 pe-6 gap-3 flex items-center justify-center rounded-full"
            >
                <div class="bg-violet-50 p-2 rounded-full">
                    <PhClock class="text-lg text-violet-500" />
                </div>

                <h1 class="font-semibold text-sm">{{ time }}</h1>
            </div>
        </div>

        <button
            class="bg-white hover:bg-violet-50 transition-all group border h-12 py-2 pe-2 ps-6 gap-4 flex items-center justify-center rounded-full"
        >
            <h1
                class="text-sm font-semibold text-violet-500 group-hover:text-violet-400"
            >
                Open Order
            </h1>

            <div
                class="bg-violet-50 group-hover:bg-violet-100 p-2 rounded-full"
            >
                <PhPower class="text-lg text-violet-500" />
            </div>
        </button>
    </div>
</template>

<script setup>
import {
    PhCalendarDots,
    PhClock,
    PhList,
    PhMagnifyingGlass,
    PhPower,
} from "@phosphor-icons/vue";
import { onMounted, ref } from "vue";
import { useMethodStore } from "../../stores/method";

const method = useMethodStore();

const time = ref("");

const now = new Date();
const options = {
    weekday: "short",
    day: "2-digit",
    month: "short",
    year: "numeric",
};
const formattedDate = new Intl.DateTimeFormat("en-US", options).format(now);

function updateTime() {
    const now = new Date();
    const options = {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    };
    time.value = new Intl.DateTimeFormat("en-US", options).format(now);
}

// Update setiap detik
setInterval(updateTime, 1000);

onMounted(() => {
    updateTime();
});
</script>

<style lang="scss" scoped></style>
