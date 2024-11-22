<template>
    <button class="h-10 px-4 rounded-lg font-semibold text-sm">
        {{ text }}
    </button>
</template>

<script setup>
defineProps(["text"]);
</script>
