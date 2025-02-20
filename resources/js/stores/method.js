import { defineStore } from "pinia";
import { useReceiptStore } from "./receipt";

export const useMethodStore = defineStore("method", {
    state: () => ({
        toasterStat: false,
        modalDeactive: false,
        modalDelete: false,
        deactiveId: null,
        deleteId: null,
        modalPyamentStat: false,
        modalPrintStat: false,
        sideBarStat: true,
        modalDetailStat: false,
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
            this.deactiveId = id;
        },
        modalDeleteFnc(id) {
            this.modalDelete = !this.modalDelete;
            this.deleteId = id;
        },
        toasterFnc() {
            this.toasterStat = true;
        },
        toasterFncClose() {
            this.toasterStat = false;
        },
        modalDetailFnc() {
            this.modalDetailStat = !this.modalDetailStat;
        },
    },
});
