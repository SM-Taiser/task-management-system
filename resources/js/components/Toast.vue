<script setup lang="ts">
const props = defineProps({
  message: String,
  type: { type: String, default: 'success' },
})
console.log(props.message);

</script>
<template>
  <div name="fade">
    <div
      :class="[
        'fixed top-5 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-white toast',
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
      ]"
    >
      {{ message }}
    </div>
    </div>    
</template>

<style scoped>
/* Fade transition for entering and leaving */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Animation to auto-remove the toast after 3 seconds */
.toast {
  animation: fadeOut 4s forwards;
}

@keyframes fadeOut {
  0% {
    opacity: 1;
  }
  80% {
    opacity: 1;
  }
  100% {
    opacity: 0;
    display: none; /* Hides the element */
  }
}
</style>
