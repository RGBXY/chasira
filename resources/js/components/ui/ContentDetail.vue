<template>
    <div class="flex items-center w-full gap-3">
        <div class="w-[45%]">
            <h1 @click="hai()" class="font-medium text-gray-500">
                {{ props.title }}
            </h1>
        </div>
        <div class="w-[55%]">
            <h1 class="font-medium text-sm">
                {{ props.value }}
            </h1>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: String,
    value: String,
});

const hai = () => {
    console.log(props.value);
    console.log("hali");
};
</script>
