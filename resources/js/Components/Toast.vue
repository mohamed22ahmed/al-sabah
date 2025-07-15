<template>
  <transition name="toast-fade">
    <div v-if="visible" :class="['fixed z-50 p-4 mb-6 rounded-lg shadow-lg flex items-center', positionClass, typeClass]" style="min-width: 250px; max-width: 350px; right: 2rem; bottom: 2rem; direction: rtl;">
      <span class="ml-3 text-lg font-bold">{{ message }}</span>
      <button @click="close" class="ml-auto text-2xl leading-none focus:outline-none">&times;</button>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch, onUnmounted, computed } from 'vue';

const props = defineProps({
  message: String,
  type: {
    type: String,
    default: 'success', // or 'error'
  },
  duration: {
    type: Number,
    default: 3000,
  },
  position: {
    type: String,
    default: 'bottom-right',
  },
});

const visible = ref(true);
let timer = null;

const close = () => {
  visible.value = false;
};

watch(
  () => props.message,
  () => {
    visible.value = true;
    if (timer) clearTimeout(timer);
    timer = setTimeout(close, props.duration);
  },
  { immediate: true }
);

onUnmounted(() => {
  if (timer) clearTimeout(timer);
});

const typeClass = computed(() =>
  props.type === 'error'
    ? 'bg-red-600 text-white'
    : 'bg-green-600 text-white'
);
const positionClass = 'right-0 bottom-0';
</script>

<style scoped>
.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: opacity 0.3s;
}
.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
}
</style>
