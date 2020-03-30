<template>
  <div class="video-page-container">
    <div class="video-wrapper">
      <div class="item-list video-options">
        <ul>
          <li
            class="item-list__item item-list__item--hide-small"
            @click="toggleActivePanel($event, 'about')"
          >
            <a href="#about">About</a>
          </li>
          <li
            class="item-list__item"
            @click="toggleActivePanel($event, 'share')"
          >
            <a href="#Share">Share</a>
          </li>
          <li
            class="item-list__item"
            @click="toggleActivePanel($event, 'use')"
          >
            <a href="#use">Use this video</a>
          </li>
          <li
            class="item-list__item"
            @click="toggleActivePanel($event, 'transcript');getTranscript()"
          >
            <a href="#transcript">Transcript</a>
          </li>
          <li
            class="item-list__item"
            @click="toggleActivePanel($event, 'clip')"
          >
            <a href="#clip">Clip</a>
          </li>
          <li class="">
            <router-link :to="{name: 'app'}">
              Home
            </router-link>
          </li>
          <li class="">
            <router-link :to="{name: 'search'}">
              Search
            </router-link>
          </li>
        </ul>
      </div>
      <div class="video-content">
        <div class="panel--left">
          <div
            v-show="activePanel === 'about'"
            :class="{active: activePanel === 'about'}"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, activePanel);"
            >Close</span>
            <about
              v-show="activePanel === 'about'"
              :description="description"
              :date_recorded="date_recorded"
              :people="speakers"
              :playlists="in_playlists"
              :current-panel="activePanel"
            />
          </div>

          <div
            v-show="activePanel === 'share'"
            :class="{active: activePanel === 'share'}"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, activePanel);"
            >Close</span>
            <share
              v-show="activePanel === 'share'"
              :current-panel="activePanel"
              :description="description"
              :title="title"
              :title_slug="title_slug"
              :date_recorded="date_recorded"
            />
          </div>

          <div
            v-show="activePanel === 'use'"
            :class="{active: activePanel === 'use'}"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, activePanel);"
            >Close</span>
            <use-this
              v-show="activePanel === 'use'"
              :current-panel="activePanel"
            />
          </div>

          <div
            v-show="activePanel === 'transcript'"
            :class="{active: activePanel === 'transcript'}"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, activePanel);"
            >Close</span>
            <p v-show="!transcriptLoaded">
              Loading...
            </p>
            <transcript
              :items="paraText"
              :current-timecode="currentTimecode"
              @updateTimecode="updateTimecode"
            />
          </div>
          <div
            v-show="activePanel === 'clip'"
            :class="{active: activePanel === 'clip'}"
            class="video-wrapper__item"
          >
            <span
              class="close-button"
              @click="toggleActivePanel($event, activePanel);"
            >Close</span>
            <clipping-tool
              v-show="activePanel === 'clip'"
              :current-panel="activePanel"
              :current-timecode="this.currentTimecode"
            />
          </div>
        </div>
        <div class="panel--right">
          <video-player
            dusk="video-player-component"
            :duration="duration"
            :options="videoOptions"
            :title="title"
            :track="track"
            :timecode="timecode"
            :video-url="video_url"
            @playbackerror="onPlayerError"
            @timeupdate="onTimeUpdate"
          />
          <div class="video-meta">
            <h1 class="video-meta__title title">
              {{ title }}
            </h1>
            <div class="video-description--mobile">
              <about
                :description="description"
                :date_recorded="date_recorded"
                :people="speakers"
                :playlists="in_playlists"
              />
            </div>
          </div>

          <div class="item-list video-options video-options--mobile">
            <ul>
              <li
                class="item-list__item item-list__item--hide-small"
                @click="toggleActivePanel($event, 'about')"
              >
                <a href="#about">About</a>
              </li>
              <li
                class="item-list__item"
                @click="toggleActivePanel($event, 'share')"
              >
                <a href="#Share">Share</a>
              </li>
              <li
                class="item-list__item"
                @click="toggleActivePanel($event, 'use')"
              >
                <a href="#use">Use this video</a>
              </li>
              <li
                class="item-list__item"
                @click="toggleActivePanel($event, 'transcript');getTranscript()"
              >
                <a href="#transcript">Transcript</a>
              </li>
              <li
                class="item-list__item"
                @click="toggleActivePanel($event, 'clip')"
              >
                <a href="#clip">Clip</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import getRouteData from '../../mixins/getRouteData';
import About from './About.vue';
import ClippingTool from './ClippingTool.vue';
import Transcript from '../Transcript.vue';
import UseThis from './UseThis.vue';
import Share from './Share.vue';
import VideoPlayer from './VideoPlayer.vue';

export default {
  name: 'VideoComponent',
  components: {
    About,
    Transcript,
    VideoPlayer,
    UseThis,
    ClippingTool,
    Share,
  },
  mixins: [getRouteData],
  data() {
    return {
      activePanel: null,
      asset_id: null,
      currentTimecode: 0,
      datastore: process.env.MIX_DATASTORE_URL,
      date_recorded: null,
      description: null,
      duration: null,
      in_playlists: null,
      speakers: null,
      thumbnailUrl: null,
      timecode: 0,
      title: null,
      title_slug: null,
      track: null,
      transcription: null,
      transcriptItems: [],
      transcriptLoaded: false,
      videoOptions: null,
      video_url: null,
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
    activePanel() {
      if (window.innerWidth < 760) {
        this.$nextTick(() => {
          if (document.querySelector('.video-wrapper__item.active')) {
            const height = document.documentElement.offsetHeight - document.querySelector('video').offsetHeight;
            document.querySelector('.video-wrapper__item.active').setAttribute('style', `height:${height}px`);
          }
        });
      }
    },
    video_url() {
      this.videoOptions = {
        autoplay: false,
        controls: true,
        fill: true,
        sources: [
          {
            src: this.video_url,
            type: 'video/mp4',
          },
        ],
      };
    },
    asset_id() {
      this.track = {
        src: `${this.datastore}videos/${this.asset_id}/transcript?format=vtt`,
        kind: 'captions',
        language: 'en',
        label: 'English',
        default: true,
      };
    },
  },
  methods: {
    onPlayerError() {
      axios
        .get(`/api/video/${this.$route.params.id}`)
        .then((response) => {
          this.videoUrl = response.data.data.video_url;
        });
    },
    toggleActivePanel(event, name) {
      const clickedElem = event.target;
      if (document.querySelector('.active-panel')) {
        document.querySelector('.active-panel').classList.remove('active-panel');
      }

      if (this.activePanel === name) {
        this.activePanel = null;
        document.querySelector('.panel--left').classList.remove('panel--left--open');
      } else {
        this.activePanel = name;
        clickedElem.classList.add('active-panel');
        document.querySelector('.panel--left').classList.add('panel--left--open');
      }
    },
    getTranscript() {
      axios
        .get(`${this.datastore}videos/${this.asset_id}/transcript?format=json`)
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
