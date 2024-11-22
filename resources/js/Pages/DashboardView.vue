<template>
    <Layout>
        <Head :title="user" />
        <div class="pt-20">Dashboard User : {{ user }}</div>
    </Layout>
</template>

<script setup>
import Layout from "../Layouts/Layout.vue";

defineProps({
    user: String,
});
</script>
