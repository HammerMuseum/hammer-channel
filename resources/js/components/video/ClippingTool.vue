<template>
  <div class="clip">
    <div class="clip__controls">
      <div class="clip__controls clip-control">
        <button class="clip-control__button" @click="setTime('start')">Set start time</button>
        <input class="clip-control__input" name="start_time" value="00:00:00" />
      </div>
      <div class="clip__controls clip-control">
        <button class="clip-control__button" @click="setTime('end')">Set end time</button>
        <input class="clip-control__input" name="end_time" value="00:00:00" />
      </div>
    </div>
    <div class="share-clip">
      <button class="share-clip__item" @click="generateLink()" name="make_link">Generate shareable link</button>
      <span class="clip-error" v-show="canGenerateClip == false">End time is before start time.</span>
      <input class="share-clip__item" name="link" />
      <button class="share-clip__item" name="copy_link" @click="copyLink()">Copy to clipboard</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      currentTimecode: {
        type: Number,
        default() {
          return 0;
        },
      }
    },
    data() {
      return {
        canGenerateClip: true
      }
    },
    mounted() {

    },
    methods: {
      setTime(input) {
        let inputField = document.querySelector('input[name=' + input + '_time]');
        inputField.value = this.convertFromSeconds(this.currentTimecode);
      },
      convertFromSeconds(timeStr) {
        return (new Date(timeStr * 1000)).toUTCString().match(/(\d\d:\d\d:\d\d)/)[0];
      },
      convertToSeconds(timeStr) {
        return new Date('1970-01-01T' + timeStr + 'Z').getTime() / 1000;
      },
      generateLink() {
        let startTime = document.querySelector('input[name=start_time]');
        let endTime = document.querySelector('input[name=end_time]');

        if (endTime.value <= startTime.value) {
          this.canGenerateClip = false;
          return false;
        }

        this.canGenerateClip = true;

        let domain = window.location.origin;
        let path = this.$route.path;
        let linkInput = document.querySelector('input[name=link]');


        let startSeconds = this.convertToSeconds(startTime.value);
        let endSeconds = this.convertToSeconds(endTime.value);

        linkInput.value = domain + path + '?start=' + startSeconds + '&end=' + endSeconds;
      },
      copyLink() {
        let link = document.querySelector('input[name=link]');
        link.select();
        document.execCommand('copy');
      },
    }
  };
</script>
