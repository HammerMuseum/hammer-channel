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
            ref="copy"
            v-model="shareableLink"
            class="clip__control__input clip__control__input--unrestrained"
          >
        </div>
        <div class="clip__controls clip__sharing">
          <!-- <a
            role="button"
            class="link link--text link--text-secondary"
            name="make_link"
            @click="generateLink()"
          >
            Generate shareable link
          </a> -->
          <span
            v-show="error"
            class="clip-error"
          >Please set a valid start and/or end time.</span>
          <a
            role="button"
            class="link link--text link--text-secondary"
            name="copy_link"
            @click="copyLink()"
          >
            Copy link to clipboard
          </a>
        </div>
      </div>
    </template>
  </VideoMeta>
</template>

<script>
export default {
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
    shareableLink() {
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
    copyLink() {
      if (this.canGenerateClip) {
        this.$refs.copy.select();
        document.execCommand('copy');
      }
    },
  },
};
</script>
