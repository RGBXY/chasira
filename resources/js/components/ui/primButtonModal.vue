<template>
    <button
        class="h-10 px-4 rounded-lg font-semibold border-primary border-2 text-primary"
    >
        {{ text }}
    </button>
</template>

<script setup>
defineProps(["text"]);
</script>
