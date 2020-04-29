<template>
  <VideoMeta>
    <template v-slot:highlighted>
      <div class="clip">
        <div class="clip__controls">
          <div class="clip__control">
            <button
              class="clip__control__button clip__control__button--left"
              @click="setTime('start')"
            >
              Set start time
            </button>
          </div>
          <div class="clip__control">
            <button
              class="clip__control__button clip__control__button--right"
              @click="setTime('end')"
            >
              Set end time
            </button>
          </div>
        </div>
        <div class="clip__controls clip__inputs">
          <input
            v-model="clipStart"
            class="clip__control__input clip__control"
            name="start_time"
            value="00:00:00"
            placeholder="00:00:00"
          >
          <input
            v-model="clipEnd"
            class="clip__control__input clip__control"
            name="end_time"
            value="00:00:00"
            placeholder="00:00:00"
          >
        </div>
        <div class="share-link">
          <input
            v-show="canGenerateClip"
            v-model="clipUrl"
            class="clip__control__input clip__control__input--unrestrained"
          >
        </div>
        <div class="clip__controls clip__sharing">
          <span
            v-show="error"
            class="clip-error"
          >Please set a valid start and/or end time.</span>
          <button
            :class="['button', 'button--action', {'button--disabled': !canGenerateClip}]"
            aria-label="Copy citation to clipboard"
            :disabled="!canGenerateClip"
            @click="copyToClipboard(clipUrl)"
          >
            Copy link to clipboard
          </button>
        </div>
        <transition name="fade">
          <div
            v-if="copied"
            class="copy-status"
          >
            Copied
          </div>
        </transition>
      </div>
    </template>
  </VideoMeta>
</template>

<script>
import CopyTo from '../../mixins/copyToClipboard';

export default {
  mixins: [CopyTo],
  props: {
    currentTimecode: {
      type: Number,
      default() {
        return 0;
      },
    },
  },
  data() {
    return {
      clipStart: null,
      clipEnd: null,
      error: false,
    };
  },
  computed: {
    canGenerateClip() {
      if (!this.clipEnd || !this.clipStart) return false;
      if (this.clipEnd !== '00:00:00' && this.clipEnd > this.clipStart) {
        return true;
      }
      return false;
    },
    clipUrl() {
      if (this.canGenerateClip) {
        const domain = window.location.origin;
        const path = this.$route.path;
        const startSeconds = this.convertToSeconds(this.clipStart);
        const endSeconds = this.convertToSeconds(this.clipEnd);
        return `${domain + path}?start=${startSeconds}&end=${endSeconds}`;
      }
      return false;
    },
  },
  methods: {
    setTime(input) {
      const inputField = document.querySelector(`input[name=${input}_time]`);
      const selectedTime = this.convertFromSeconds(this.currentTimecode);
      inputField.value = selectedTime;
      if (input === 'start') {
        this.clipStart = selectedTime;
      } else if (input === 'end') {
        this.clipEnd = selectedTime;
      }
    },
    convertFromSeconds(timeStr) {
      return (new Date(timeStr * 1000)).toUTCString().match(/(\d\d:\d\d:\d\d)/)[0];
    },
    convertToSeconds(timeStr) {
      return new Date(`1970-01-01T${timeStr}Z`).getTime() / 1000;
    },
  },
};
</script>
