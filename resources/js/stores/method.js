import { defineStore } from 'pinia';
import { useReceiptStore } from './receipt';

export const useMethodStore = defineStore('method', {
  state: () => ({
    toasterStat: false,
    toasterMessage: null,
    modalPaymentStat: false,
    modalPrintStat: false,
    sideBarStat: false,
    SideModalStat: false,
    modalCustomerStat: false,
    modalDiscountStat: false,
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
      this.modalPrintStat = false;
    },
    modalDeactiveFnc(id) {
      this.modalDeactive = !this.modalDeactive;
      this.deactiveId = id;
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
    modalCustomerFnc() {
      this.modalCustomerStat = true;
    },
    modalCustomerClose() {
      this.modalCustomerStat = false;
    },
    modalDiscountFnc() {
      this.modalDiscountStat = true;
    },
    modalDiscountClose() {
      this.modalDiscountStat = false;
    },
  },
});
