import { defineStore } from "pinia";

export const useReceiptStore = defineStore("receipt", {
    state: () => ({
        products: [],
        change: 0,
    }),
    actions: {},
});
