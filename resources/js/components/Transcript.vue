<template>
  <div
    v-if="items"
    class="video-meta__transcript transcript"
  >
    <h3 class="video-meta__title">
      Transcript
    </h3>
    <button @click="downloadTranscript">
      Download
    </button>
    <p
      v-for="item in items"
      :key="item.id"
      class="transcript__paragraph"
      :class="{ 'transcript__paragraph--active': isActive(item.start, item.end, item.id)}"
      @click="$emit('updateTimecode', item.start)"
    >
      {{ item.message }}
    </p>
  </div>
</template>

<script>
import { saveAs } from 'file-saver';
import VueScrollTo from 'vue-scrollto';

export default {
  props: {
    currentTimecode: {
      type: Number,
      default() {
        return 0;
      },
    },
    items: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      currentPara: null,
      scrollInProgress: false,
    };
  },
  watch: {
    currentPara() {
      const self = this;
      const options = {
        container: '.video-wrapper__item.active',
        easing: 'ease-in-out',
        offset: -200,
        force: true,
        onStart() {
          self.scrollInProgress = true;
        },
        onDone() {
          self.scrollInProgress = false;
        },
        x: false,
        y: true,
      };
      const el = this.$el.getElementsByClassName('transcript__paragraph--active')[0];
      VueScrollTo.scrollTo(el, 1600, options);
    },
  },
  methods: {
    isActive(start, end, paraId) {
      if (this.currentTimecode >= start && this.currentTimecode <= end) {
        this.currentPara = paraId;
        return true;
      }
      return false;
    },
    downloadTranscript() {
      const output = this.items.map((el) => `${el.message}${'\r\n\r\n'}`);
      const blob = new Blob(output, { type: 'text/plain;charset=utf-8' });
      saveAs(blob, 'transcript.txt');
    },
  },
};
</script>
