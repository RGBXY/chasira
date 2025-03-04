<template>
    <div
        class="w-full justify-between items-center flex-row min-h-screen bg-gray-50"
    >
        <div
            :class="[
                'fixed h-full transition-all duration-300',
                method.sideBarStat ? 'w-[250px]' : 'w-[85px]',
            ]"
        >
            <SideBar />
        </div>

        <!-- Konten utama -->
        <div
            :class="[
                'flex-1 pb-5 transition-all duration-300',
                method.sideBarStat ? 'ml-[250px]' : 'ml-[85px]',
            ]"
        >
            <TopBar />
            <div class="flex items-center justify-center w-full">
                <slot></slot>
            </div>
        </div>
        <Toaster />
    </div>
</template>

<script setup>
import SideBar from "../components/layouts/SideBar.vue";
import TopBar from "../components/layouts/TopBar.vue";
import Toaster from "../components/modal/Toaster.vue";
import { useMethodStore } from "../stores/method";

const method = useMethodStore();
</script>
