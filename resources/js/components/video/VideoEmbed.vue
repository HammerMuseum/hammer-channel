<template>
  <div
    ref="videoPlayer"
  >
    <VideoPlayer
      :duration="video.duration"
      :options="options"
      :retry-sources="retrySources"
      :title="video.title"
      :poster="poster"
      :timecode="timecode"
      :track="track"
      embed
      @playbackerror="onPlayerError"
      @timeupdate="onTimeUpdate"
    />
  </div>
</template>

<script>
import axios from 'axios';
import VideoPlayer from './VideoPlayer.vue';

export default {
  name: 'VideoEmbed',
  components: {
    VideoPlayer,
  },
  props: {
    video: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      currentTimecode: 0,
      datastore: process.env.MIX_DATASTORE_URL,
      options: {
        sources: [{
          src: this.video.src,
          type: 'video/mp4',
        }],
      },
      retrySources: null,
      timecode: 0,
      track: null,
    };
  },
  computed: {
    poster() {
      const video = this.video;
      if (!video.thumbnailId) {
        return '';
      }
      return `/images/${video.thumbnailId}/large`;
    },
  },
  mounted() {
    this.setVideo();
  },
  methods: {
    setVideoSource(url) {
      const sources = [{
        src: url,
        type: 'video/mp4',
      }];
      this.options = { ...this.options, sources };
      this.retrySources = null;
    },
    setVideo() {
      this.setVideoSource(this.video.src);
      this.track = {
        src: `${this.datastore}videos/${this.video.asset_id}/transcript?format=vtt`,
        kind: 'captions',
        language: 'en',
        label: 'English',
        default: false,
      };
    },
    onPlayerError() {
      axios
        .get(`/api/video/${this.video.asset_id}/${this.video.title_slug}`)
        .then((response) => {
          this.retrySources = [{ src: response.data.video.src, type: 'video/mp4' }];
        }).catch((err) => {
          console.log(err);
        });
    },
    onTimeUpdate(value) {
      this.currentTimecode = value;
    },
    updateTimecode(value) {
      this.timecode = value;
    },
  },
};
</script>