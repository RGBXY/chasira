<template>
    <Head>
        <title>Chasira</title>
        <meta name="description" content="This is default desc" />
    </Head>

    <div class="w-full min-h-screen bg-gray-50">
        <TopBar />
        <SideBar />
        <slot></slot>
    </div>
</template>

<script setup>
import SideBar from "../components/layouts/SideBar.vue";
import TopBar from "../components/layouts/TopBar.vue";
</script>
