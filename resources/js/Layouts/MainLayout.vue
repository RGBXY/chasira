<template>
    <Head>
        <title>Chasira</title>
        <meta name="description" content="This is default desc" />
    </Head>

    <div class="w-full min-h-screen flex bg-gray-50">
        <div class="w-[70%] pb-5">
            <TopBar />
            <slot></slot>
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
</script>
