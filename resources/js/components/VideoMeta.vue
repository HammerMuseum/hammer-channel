<template>
  <div class="video-meta">
    <div class="video-meta__inner video-meta__highlighted">
      <slot name="highlighted" />
    </div>
    <div class="video-meta__inner">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'VideoMeta',
};
</script>
