<template>
  <div class="video-page-container">
    <div class="video-wrapper">
      <div class="video-content">
        <div class="panel--left">
          <div
            v-show="currentPanel == 'about'"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, currentPanel);"
            >Close</span>
            <about
              v-show="currentPanel == 'about'"
              :description="description"
              :date="date"
              :keywords="keywords"
              :program-series="programSeries"
              :current-panel="currentPanel"
            />
          </div>

          <div
            v-show="currentPanel == 'share'"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, currentPanel);"
            >Close</span>
            Share this item
          </div>


          <div
            v-show="currentPanel == 'use'"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, currentPanel);"
            >Close</span>
            Use this item
          </div>


          <div
            v-show="currentPanel == 'transcript'"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, currentPanel);"
            >Close</span>
            <transcript
              :items="paraText"
              :current-timecode="currentTimecode"
              @updateTimecode="updateTimecode"
            />
          </div>
        </div>
        <div class="panel--right">
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
          <div class="video-description--mobile">
            <about
              :description="description"
              :date="date"
              :keywords="keywords"
              :program-series="programSeries"
              :current-panel="currentPanel"
            />
          </div>
        </div>
      </div>

      <div class="item-list sidebar">
        <ul>
          <li
            class="item-list__item item-list__item--hide-small"
            @click="toggleActivePanel($event, 'about')"
          >
            About
          </li>
          <li
            class="item-list__item"
            @click="toggleActivePanel($event, 'share')"
          >
            Share
          </li>
          <li
            class="item-list__item"
            @click="toggleActivePanel($event, 'use')"
          >
            Use this video
          </li>
          <li
            class="item-list__item"
            @click="toggleActivePanel($event, 'transcript');getTranscript()"
          >
            Transcript
          </li>
        </ul>
      </div>
    </div>
    <div class="related-content">
      <div class="related-content__item">
        Tags
      </div>
      <div class="related-content__item">
        Related
      </div>
      <div class="related-content__item">
        Attend
      </div>
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
          fluid: true,
          aspectRatio: '16:9',
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
    toggleActivePanel(event, name) {
      const clickedElem = event.target;
      if (this.currentPanel === name) {
        this.currentPanel = null;
        clickedElem.classList.remove('active-panel');
        document.querySelector('.panel--left').classList.remove('panel--left--open');
        // document.querySelector('.video-info--title').classList.remove('hidden');
      } else {
        this.currentPanel = name;
        clickedElem.classList.add('active-panel');
        document.querySelector('.panel--left').classList.add('panel--left--open');
        // document.querySelector('.video-info--title').classList.add('hidden');
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

<style scoped>
  .related-content {
    display: flex;
    justify-content: center;
  }

  .related-content__item {
    padding: 1.5rem;
    max-width: 65px;
    text-align: center;
  }
</style>
