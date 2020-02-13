<template>
  <div class="video-wrapper">
    <div>
      <video-player
        dusk="video-player-component"
        :options="videoOptions"
        :title="title"
        :track="track"
        :timecode="timecode"
        :video-url="videoUrl"
        @error="onPlayerError"
        @timeupdate="onTimeUpdate"
      />
    </div>
    <div>{{ currentTimecode }}</div>
    <button @click="getTranscript">Load transcript</button>
    <button @click="onPlayerError">refresh source</button>
    <button @click="updateTimecode(currentTimecode - 10)"><< 10s</button>
    <button @click="updateTimecode(currentTimecode + 10)">10s >></button>
    <transcript
      :items="paraText"
      :current-timecode="currentTimecode"
      @updateTimecode="updateTimecode"
    />
  </div>
</template>

<script>
import axios from 'axios';
import VideoPlayer from './VideoPlayer.vue';
import Transcript from './Transcript.vue';

export default {
  name: 'VideoComponent',
  components: {
    Transcript,
    VideoPlayer,
  },
  data() {
    return {
      assetId: null,
      datastore: process.env.MIX_DATASTORE_URL,
      date: this.date,
      description: this.description,
      keywords: this.keywords,
      thumbnailUrl: null,
      currentTimecode: 0,
      timecode: 0,
      title: this.title,
      track: null,
      transcriptItems: [],
      transcriptLoaded: false,
      videoOptions: this.videoOptions,
      videoUrl: null,
    };
  },
  computed: {
    paraText() {
      if (!this.transcriptLoaded) {
        return [];
      }

      const keys = Object.keys(this.transcriptItems);
      return keys.map((key) => {
        const para = this.transcriptItems[key];
        const str = para.map((item) => item.value);
        return {
          id: key,
          message: str.join(' '),
          start: para[0].time / 1000,
          end: para[para.length - 1].time / 1000,
        };
      });
    },
  },
  watch: {
    assetId() {
      this.track = {
        src: `${this.datastore}videos/${this.assetId}/transcript?format=vtt`,
        kind: 'captions',
        language: 'en',
        label: 'English',
        default: true,
      };
    },
  },
  mounted() {
    const assetId = this.$route.params.id;
    axios
      .get(`/viewJson/${assetId}`)
      .then((response) => {
        const data = response.data.data;
        this.title = data.title;
        this.description = data.description;
        this.assetId = data.asset_id;
        this.date = data.date_recorded;
        this.thumbnailUrl = data.thumbnail_url;
        this.keywords = data.tags;
        this.videoOptions = {
          autoplay: false,
          controls: true,
          sources: [
            {
              src: data.video_url,
              type: 'video/mp4',
            },
          ],
        };
      });
  },
  methods: {
    onPlayerError() {
      axios
        .get(`/viewJson/${this.$route.params.id}`)
        .then((response) => {
          this.videoUrl = response.data.data.video_url;
        });
    },
    getTranscript() {
      axios
        .get(`${this.datastore}videos/${this.assetId}/transcript?format=json`)
        .then((response) => {
          const paras = {};
          response.data.data.words.forEach((item) => {
            if (paras[item.paragraphId] === undefined) {
              paras[item.paragraphId] = [];
            }
            paras[item.paragraphId].push(item);
          });
          this.transcriptItems = paras;
          this.transcriptLoaded = true;
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
