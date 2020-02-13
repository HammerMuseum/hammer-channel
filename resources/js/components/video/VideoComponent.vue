<template>
  <div class="video-page-wrapper horizontal-layout">
    <div class="item-list sidebar">
      <ul>
        <li class="item-list__item" @click="toggleActivePanel($event, 'about')">About</li>
        <li class="item-list__item" @click="toggleActivePanel($event, 'share')">Share</li>
        <li class="item-list__item" @click="toggleActivePanel($event, 'use')">Use this video</li>
        <li class="item-list__item" @click="toggleActivePanel($event, 'transcript')">Transcript</li>
      </ul>
    </div>
    <div class="video-wrapper horizontal-layout">
      <!--<transition name="slide">-->
      <about
        v-show="currentPanel == 'about'"
        :description="description"
        :date="date"
        :keywords="keywords"
        :programSeries="programSeries"
        :currentPanel="currentPanel"
      ></about>
      <div class="share video-wrapper__item" v-show="currentPanel == 'share'">
        <span class="close-button" @click="toggleActivePanel($event, 'share')">X</span>
        Share buttons go here.
      </div>
      <div class="use video-wrapper__item" v-show="currentPanel == 'use'">
        <span class="close-button" @click="toggleActivePanel($event, 'use')">X</span>
        Usage information goes here.
      </div>
      <div class="transcript video-wrapper__item" v-show="currentPanel == 'transcript'">
        <span class="close-button" @click="toggleActivePanel($event, 'transcript')">X</span>
        Transcript goes here.
      </div>
      <!--</transition>-->
      <video-player
        dusk="video-player-component"
        :options="videoOptions"
        :title="title"
        :keywords="keywords"
        :track="track"
        :timecode="timecode"
        :video-url="videoUrl"
        @error="onPlayerError"
        @timeupdate="onTimeUpdate"
      />
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
  </div>
</template>

<script>
import axios from 'axios';
import VideoPlayer from './VideoPlayer.vue';
import About from './AboutComponent.vue';
import Transcript from '../Transcript.vue';

export default {
  name: 'VideoComponent',
  components: {
    About,
    Transcript,
    VideoPlayer,
  },
  data() {
    return {
      assetId: null,
      currentPanel: null,
      currentTimecode: 0,
      datastore: process.env.MIX_DATASTORE_URL,
      date: this.date,
      description: this.description,
      keywords: this.keywords,
      programSeries: this.programSeries,
      thumbnailUrl: null,
      timecode: 0,
      title: this.title,
      track: null,
      transcription: null,
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
        this.videoUrl = data.video_url;
        this.keywords = data.tags;
        this.programSeries = data.program_series;
        this.videoOptions = {
          autoplay: false,
          controls: true,
          sources: [
            {
              src: data.videoUrl,
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
    toggleActivePanel(event, name) {
      const clickedElem = event.target;
      if (this.currentPanel === name) {
        this.currentPanel = null;
        clickedElem.classList.remove('active-panel');
        document.querySelector('.video-info--title').classList.remove('hidden');
      } else {
        this.currentPanel = name;
        clickedElem.classList.add('active-panel');
        document.querySelector('.video-info--title').classList.add('hidden');
      }
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
