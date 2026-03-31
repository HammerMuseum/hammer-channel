<template>
  <span>{{ displayValue }}</span>
</template>

<script setup>
import { ref, watch, onBeforeUnmount } from 'vue';

const props = defineProps({
  value: { type: Number, required: true }, // target value
  duration: { type: Number, default: 500 }, // ms
});

// Reactive value actually shown in the template
const displayValue = ref(0);
// Holds the current requestAnimationFrame ID so we can cancel it
let frameId = null;

function animate(to, duration) {
  // Cancel any in‑progress animation before starting a new one
  if (frameId != null) cancelAnimationFrame(frameId);

  const from = 0;
  const start = performance.now(); // Timestamp when the animation begins
  const diff = to - from;

  // Called on each animation frame
  const step = (now) => {
    const elapsed = now - start;
    const progress = Math.min(elapsed / duration, 1);

    // Interpolate between from and to, then round to an integer
    displayValue.value = Math.round(from + diff * progress);

    if (progress < 1) {
      // Not finished yet; schedule the next frame
      frameId = requestAnimationFrame(step);
    } else {
      // Done: clear the frame ID
      frameId = null;
    }
  };

  // Start first animation frame
  frameId = requestAnimationFrame(step);
}

// Whenever the `value` prop changes
watch(
  () => props.value,
  (newVal) => {
    // Start a fresh animation from 0 up to the new value
    animate(newVal ?? 0, props.duration);
  },
  { immediate: true }, // Also run once on mount
);

// Clean up any pending animation when the component is about to unmount
onBeforeUnmount(() => {
  if (frameId != null) cancelAnimationFrame(frameId);
});
</script>
