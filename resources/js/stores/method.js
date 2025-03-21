import { defineStore } from 'pinia';
import { useReceiptStore } from './receipt';

export const useMethodStore = defineStore('method', {
  state: () => ({
    toasterStat: false,
    toasterMessage: null,
    modalStat: false,
    modalParam: null,
    modalPaymentStat: false,
    modalPrintStat: false,
    sideBarStat: false,
    SideModalStat: false,
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
    modalFnc(id) {
      this.modalStat = !this.modalStat;
      this.modalParam = id;
    },
    toasterFnc(message) {
      this.toasterStat = true;
      this.toasterMessage = message;
    },
    toasterFncClose() {
      this.toasterStat = false;
    },
    sideModalFnc() {
      this.SideModalStat = !this.SideModalStat;
    },
  },
});
