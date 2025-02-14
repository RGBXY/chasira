<script setup>
defineProps({
    data: Array, // Data yang akan ditampilkan dalam tabel
    header: Array, // Konfigurasi header tabel
});
</script>

<template>
    <div class="w-full border rounded-lg">
        <table class="table-auto w-full rounded-lg">
            <!-- Header -->
            <thead>
                <tr class="border-b bg-gray-100">
                    <th
                        v-for="col in header"
                        :key="col.key"
                        class="text-start p-3"
                    >
                        {{ col.label }}
                    </th>
                    <!-- Tampilkan kolom "Actions" hanya jika slot actions digunakan -->
                    <th v-if="$slots.actions" class="text-start p-3">
                        Actions
                    </th>
                </tr>
            </thead>

            <!-- Body -->
            <tbody>
                <tr v-for="(row, index) in data" :key="index" class="border-t">
                    <td v-for="col in header" :key="col.key" class="p-3">
                        <slot :name="col.key" :row="row">
                            {{ row[col.key] }}
                            <!-- Default jika slot tidak digunakan -->
                        </slot>
                    </td>

                    <!-- Tampilkan kolom actions hanya jika slot actions digunakan -->
                    <td v-if="$slots.actions" class="p-3">
                        <slot name="actions" :row="row"></slot>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
