<template>
    <Head>
        <title>Chasira</title>
        <meta name="description" content="This is default desc" />
    </Head>

    <div
        class="w-full justify-between items-center flex-row min-h-screen bg-gray-50"
    >
        <SideBar />

        <div :class="method.sideBarStat ? 'ps-[250px]' : 'ps-[85px]'">
            <TopBar />
            <div class="">
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
