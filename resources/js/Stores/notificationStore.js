import { defineStore } from 'pinia';

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    show: false,
    message: '',
    type: 'success', // or 'error'
    duration: 3000,
  }),
  actions: {
    notify({ message, type = 'success', duration = 3000 }) {
      this.message = message;
      this.type = type;
      this.duration = duration;
      this.show = true;
    },
    close() {
      this.show = false;
    },
  },
});
