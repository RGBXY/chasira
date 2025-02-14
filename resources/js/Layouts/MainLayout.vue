<template>
    <Head>
        <title>Chasira</title>
        <meta name="description" content="This is default desc" />
    </Head>

    <div class="w-full min-h-screen flex bg-gray-50">
        <div
            :class="method.sideBarStat ? 'ps-[250px]' : 'ps-[85px]'"
            class="w-[75%] pb-5"
        >
            <TopBar />
            <div class="flex items-center justify-center w-full">
                <slot></slot>
            </div>
        </div>

        <ReceiptForm />
        <SideBar />
        <Toaster />
    </div>
</template>

<script setup>
import ReceiptForm from "../components/layouts/ReceiptForm.vue";
import SideBar from "../components/layouts/SideBar.vue";
import TopBar from "../components/layouts/TopBar.vue";
import Toaster from "../components/modal/Toaster.vue";
import { useMethodStore } from "../stores/method";

const method = useMethodStore();
</script>
