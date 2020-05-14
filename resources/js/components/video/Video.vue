<template>
  <div class="container">
    <div class="page-wrapper page-wrapper--frame page--video">
      <header class="video-meta">
        <h1 class="heading heading--primary video-meta__title">
          {{ title }}
        </h1>
        <div class="video-meta__date">
          {{ new Date(date_recorded) | dateFormat('MMM D, YYYY') }}
        </div>
      </header>
      <div class="panels">
        <div class="panel--left">
          <VideoPlayer
            :duration="duration"
            :options="videoOptions"
            :poster="poster"
            :title="title"
            :track="track"
            :timecode="timecode"
            :video-url="src"
            @playbackerror="onPlayerError"
            @timeupdate="onTimeUpdate"
          />
        </div>
        <div class="panel--right">
          <BTabs
            class="vp__tabs"
            lazy
          >
            <BTab active>
              <template v-slot:title>
                <SvgIcon
                  name="description"
                  title="Description"
                />
                <h3 class="vp__tabs__label">
                  Description
                </h3>
              </template>
              <About
                :description="description"
                :date-recorded="date_recorded"
                :people="speakers"
                :playlists="in_playlists"
                :topics="topics"
              />
            </BTab>

            <BTab
              ref="transcript"
              class="tab--transcript"
            >
              <template v-slot:title>
                <SvgIcon
                  name="transcript"
                  title="Transcript"
                />
                <h3 class="vp__tabs__label">
                  Transcript
                </h3>
              </template>
              <Transcript
                :items="processedTranscript"
                :current-timecode="currentTimecode"
                @updateTimecode="updateTimecode"
              />
            </BTab>

            <BTab>
              <template v-slot:title>
                <SvgIcon
                  name="clip"
                  title="Clip"
                />
                <h3 class="vp__tabs__label">
                  Clip
                </h3>
              </template>
              <ClippingTool
                :current-timecode="currentTimecode"
              />
            </BTab>

            <BTab>
              <template v-slot:title>
                <SvgIcon
                  name="share"
                  title="Share"
                />
                <h3 class="vp__tabs__label">
                  Share
                </h3>
              </template>
              <Share
                :title="title"
                :date="date_recorded"
              />
            </BTab>

            <BTab>
              <template v-slot:title>
                <SvgIcon
                  name="related"
                  title="Related"
                />
                <h3 class="vp__tabs__label">
                  Related
                </h3>
              </template>
              <div>Related Content</div>
            </BTab>
          </BTabs>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { debounce } from 'lodash';
import { BTabs, BTab } from 'bootstrap-vue';
import getRouteData from '../../mixins/getRouteData';
import SvgIcon from '../base/SvgIcon.vue';
import About from './About.vue';
import ClippingTool from './ClippingTool.vue';
import Transcript from '../Transcript.vue';
import Share from './Share.vue';
import VideoPlayer from './VideoPlayer.vue';
import { store } from '../../store';

export default {
  name: 'VideoComponent',
  components: {
    About,
    BTabs,
    BTab,
    ClippingTool,
    Share,
    SvgIcon,
    Transcript,
    VideoPlayer,
  },
  mixins: [getRouteData],
  data() {
    return {
      asset_id: null,
      currentTimecode: 0,
      datastore: process.env.MIX_DATASTORE_URL,
      date_recorded: null,
      description: null,
      duration: null,
      in_playlists: [],
      speakers: [],
      thumbnailId: null,
      timecode: 0,
      title: null,
      topics: [],
      track: null,
      transcript: [],
      transcriptLoaded: false,
      videoOptions: null,
      src: null,
    };
  },
  computed: {
    processedTranscript() {
      if (!this.transcript) {
        return [];
      }
      const keys = Object.keys(this.transcript);
      return keys.map((key) => {
        const para = this.transcript[key];
        const str = para.map((item) => item.value);
        return {
          id: key,
          message: str.join(' '),
          start: para[0].time / 1000,
          end: para[para.length - 1].time / 1000,
        };
      });
    },
    poster() {
      if (!this.thumbnailId) {
        return '';
      }
      return `/images/${this.thumbnailId}/large`;
    },
    transcriptInit() {
      return store.transcriptInit;
    },
  },
  watch: {
    src() {
      this.videoOptions = {
        autoplay: false,
        controlBar: {
          muteToggle: false,
          pictureInPictureToggle: false,
          progressControl: {
            keepTooltipsInside: true,
          },
        },
        textTrackSettings: false,
        controls: true,
        fill: true,
        sources: [
          {
            src: this.src,
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
        default: false,
      };
    },
    transcriptInit(init) {
      if (init && !this.transcriptLoaded) {
        this.fetchTranscript();
      }
      this.setTranscriptHeight();
    },
  },
  mounted() {
    document.body.classList.add('vp');
    window.addEventListener('resize', debounce(this.setTranscriptHeight, 200));
  },
  destroyed() {
    document.body.classList.remove('vp');
  },
  methods: {
    setTranscriptHeight() {
      const el = this.$refs.transcript.$el;
      const h = window.innerHeight - el.offsetTop - 56;
      el.style.maxHeight = `${h}px`;
    },
    onPlayerError() {
      axios
        .get(`/api/video/${this.$route.params.id}`)
        .then((response) => {
          this.src = response.data.src;
        }).catch((err) => {
          console.log(err);
        });
    },
    fetchTranscript() {
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
          this.transcript = paras;
          this.transcriptLoaded = true;
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
