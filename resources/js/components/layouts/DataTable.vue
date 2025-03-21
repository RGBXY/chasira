<script setup>
defineProps({
  data: Array, // Data yang akan ditampilkan dalam tabel
  header: Array, // Konfigurasi header tabel
});
</script>

<template>
  <div class="w-full overflow-x-auto">
    <table class="table-auto w-full overflow-hidden">
      <!-- Header -->
      <thead class="">
        <tr class="rounded-lg">
          <th
            v-for="col in header"
            :key="col.key"
            class="text-start p-3 font-semibold uppercase text-gray-600"
          >
            {{ col.label }}
          </th>
          <!-- Tampilkan kolom "Actions" hanya jika slot actions digunakan -->
          <th
            v-if="$slots.actions"
            class="text-start p-3 font-semibold uppercase text-gray-600"
          >
            Actions
          </th>
        </tr>
      </thead>

      <!-- Body -->
      <tbody>
        <tr
          v-for="(row, index) in data"
          :key="index"
          class="border-t hover:bg-gray-50"
        >
          <td v-for="col in header" :key="col.key" class="p-3 text-gray-600">
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
