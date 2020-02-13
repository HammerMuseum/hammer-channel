<template>
  <div
    v-if="items"
    class="transcript"
  >
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
        container: '.transcript',
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
      // const blob = new Blob(this.items)
    }
  },
};
</script>

<style scoped>
p {
  font-size: 16px;
}

.transcript__paragraph {
  text-align: left;
}

.transcript {
  overflow: scroll;
  height: 50vh;
  font-size: 12px;
  text-align: left;
  color: #999;
}

.transcript__paragraph {
  transition: all 0.4s;
}

.transcript__paragraph--active {
  color: #000;
}

@media screen and (min-width: 850px) {
  .transcript {
    overflow: scroll;
    height: 100%;
  }
}
</style>
