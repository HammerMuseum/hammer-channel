<template>
  <div class="container">
    <div class="page-wrapper page--video">
      <RouterLink
        v-if="prevRoute"
        class="breadcrumb"
        :to="prevRoute"
      >
        {{ breadcrumb }}
      </RouterLink>
      <header class="video-meta video-meta__header">
        <h1 class="heading heading--primary video-meta__title">
          {{ title }}
        </h1>
        <div
          v-show="date_recorded"
          class="video-meta__date"
        >
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
              <RelatedContent :items="relatedItems" />
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
import RelatedContent from '../RelatedContent.vue';
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
    RelatedContent,
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
      prevRoute: null,
      relatedItems: [],
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
    breadcrumb() {
      const routeName = this.prevRoute.name === null ? 'home' : this.prevRoute.name;
      return `Return to ${routeName} page`;
    },
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

      this.fetchRelatedContent();
    },
    transcriptInit(init) {
      if (init && !this.transcriptLoaded) {
        this.fetchTranscript();
      }
      this.setTranscriptHeight();
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.prevRoute = from;
    });
  },
  mounted() {
    document.body.classList.add('vp');
    window.addEventListener('resize', debounce(this.setTranscriptHeight, 200));
    this.setupObservers();
  },
  destroyed() {
    document.body.classList.remove('vp');
  },
  methods: {
    setupObservers() {
      const stickyElm = document.querySelector('.panel--left');
      const observer = new IntersectionObserver(
        this.callback,
        { threshold: [1] },
      );
      observer.observe(stickyElm);
    },
    callback(e) {
      document.querySelector('.panels').classList.toggle('is-sticky', e[0].intersectionRatio < 1);
    },
    setTranscriptHeight() {
      if (!this.$refs.transcript) return;
      const el = this.$refs.transcript.$el;
      if (window.innerWidth > 960) {
        const h = window.innerHeight - el.offsetTop - 56;
        el.style.maxHeight = `${h}px`;
      } else {
        el.style.maxHeight = '';
      }
    },
    onPlayerError() {
      axios
        .get(`/api/video/${this.$route.params.id}/${this.$route.params.slug}`)
        .then((response) => {
          this.src = response.data.src;
        }).catch((err) => {
          console.log(err);
        });
    },
    fetchRelatedContent() {
      axios
        .get(`${this.datastore}videos/${this.asset_id}/related`)
        .then((response) => {
          this.relatedItems = response.data.data;
        }).catch((err) => {
          console.error(err);
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
