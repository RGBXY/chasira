import { defineStore } from "pinia";

export const useReceiptStore = defineStore("receipt", {
    state: () => ({
        products: [],
        customers: [],
        change: 0,
    }),
    actions: {},
});
