import { defineStore } from "pinia";
import { useReceiptStore } from "./receipt";

export const useMethodStore = defineStore("method", {
    state: () => ({
        modalDeactive: false,
        deleteId: null,
        modalPyamentStat: false,
        modalPrintStat: false,
        sideBarStat: false,
        pay: 0,
    }),

    actions: {
        sideBarStatFnc() {
            this.sideBarStat = !this.sideBarStat;
        },
        modalPaymentFnc() {
            this.modalPyamentStat = !this.modalPyamentStat;
        },
        modalPrintFnc(pay) {
            this.pay = pay;
            this.modalPrintStat = true;
        },
        modalPrintFncClose() {
            const receipt = useReceiptStore();
            receipt.products = [];
            this.modalPrintStat = false;
        },
        modalDeactiveFnc(id) {
            this.modalDeactive = !this.modalDeactive;
            this.deleteId = id;
        },
    },
});
