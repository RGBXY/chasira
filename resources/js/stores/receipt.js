import { defineStore } from 'pinia';

export const useReceiptStore = defineStore('receipt', {
  state: () => ({
    products: [],
    customer: null,

    discountNominal: 0,
    discountPercent: null,
    subtotal: 0,

    change: 0,
    transaction_id: 18,
    receiptStat: false,

    refresh: false,
  }),
  actions: {
    receiptStatFnc() {
      this.receiptStat = !this.receiptStat;
    },
  },
});
