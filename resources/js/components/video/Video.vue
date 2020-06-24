<template>
  <div class="container">
    <div
      v-if="video"
      id="main"
      class="page-wrapper page--video"
    >
      <div class="video-meta__breadcrumb">
        <RouterLink
          v-if="prevRoute"
          class="link--text link--text-secondary"
          :to="prevRoute"
        >
          {{ breadcrumb }}
        </RouterLink>
      </div>
      <header class="video-meta video-meta__header">
        <h1 class="heading heading--primary video-meta__title">
          {{ video.title }}
        </h1>
        <div
          v-show="video.date_recorded"
          class="video-meta__date"
        >
          {{ new Date(video.date_recorded) | dateFormat('MMM D, YYYY') }}
        </div>
      </header>
      <div
        ref="panels"
        class="panels"
      >
        <div
          ref="videoPlayer"
          class="panel--left"
        >
          <VideoPlayer
            :options="options"
            :retry-sources="retrySources"
            :title="video.title"
            :poster="poster"
            :timecode="timecode"
            :track="track"
            @playbackerror="onPlayerError"
            @timeupdate="onTimeUpdate"
            @timecode-reset="onTimecodeReset"
          />
        </div>
        <div class="panel--right">
          <BTabs
            v-model="tabIndex"
            class="vp__tabs"
            lazy
          >
            <BTab active>
              <template v-slot:title>
                <SvgIcon
                  name="info"
                  title="Information"
                />
                <h3 class="vp__tabs__label">
                  Info
                </h3>
              </template>
              <About
                :description="video.description"
                :date-recorded="video.date_recorded"
                :people="video.speakers"
                :playlists="video.in_playlists"
                :topics="video.topics"
              />
            </BTab>

            <BTab
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
                :error="transcriptError"
                :items="processedTranscript"
                :current-timecode="currentTimecode"
                @update-timecode="updateTimecode"
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
                :title="video.title"
                :date="video.date_recorded"
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
              <RelatedContent
                :items="relatedContent"
                :tags="video.tags"
              />
            </BTab>
          </BTabs>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { BTabs, BTab } from 'bootstrap-vue';
import debounce from 'lodash/debounce';
import throttle from 'lodash/throttle';
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
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      currentTimecode: 0,
      datastore: process.env.MIX_DATASTORE_URL,
      debouncedResizeObserver: null,
      options: {
        sources: null,
      },
      prevRoute: null,
      relatedContent: [],
      retrySources: null,
      tabIndex: 0,
      timecode: 0,
      track: null,
      transcript: [],
      transcriptLoaded: false,
      transcriptError: false,
      video: null,
    };
  },
  computed: {
    breadcrumb() {
      const routeName = this.prevRoute.name === null || this.prevRoute.name === 'app' ? 'home' : this.prevRoute.name;
      return `Return to ${routeName} page`;
    },
    processedTranscript() {
      if (!this.transcript) {
        return [];
      }
      const { paragraphs, speakers } = this.transcript;
      return Object.keys(paragraphs).map((id) => {
        const para = paragraphs[id];
        const paraStart = para[0].time;
        const paraEnd = para[para.length - 1].time;
        const duration = (paraEnd - paraStart);

        let speaker = '';
        const speakerId = para[0].speaker;
        if (speakerId !== null && Object.hasOwnProperty.call(speakers, speakerId)) {
          speaker = speakers[speakerId].name;
        }

        // VideoJS compatible timecode values.
        const start = paraStart / 1000;
        const end = paraEnd / 1000;

        // Formatted timecode for display.
        const timecode = new Date(paraStart).toISOString().slice(11, -5);

        // Text content.
        const message = para.map((item) => item.value).join(' ');

        return {
          id,
          message,
          speaker,
          start,
          end,
          duration,
          timecode,
        };
      });
    },
    poster() {
      const video = this.video;
      if (!video.thumbnailId) {
        return '';
      }
      return `/images/${video.thumbnailId}/large`;
    },
    transcriptInit() {
      return store.transcriptInit;
    },
  },
  watch: {
    video() {
      this.updateVideo();
      this.$announcer.set(`The page for video titled: ${this.video.title}, has loaded`);
      document.title = this.video.title;
    },
    transcriptInit(init) {
      if (init && !this.transcriptLoaded) {
        this.fetchTranscript();
      }
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.prevRoute = from;
    });
  },
  beforeRouteUpdate(to, from, next) {
    axios.get(`/api${to.path}`).then(({ data }) => {
      this.setData(data);
      next();
    });
  },
  mounted() {
    document.body.classList.add('vp');
    this.debouncedResizeListener = debounce(this.onResize, 1000);
    this.throttledScrollListener = throttle(this.onScroll, 100);
    window.addEventListener('resize', this.debouncedResizeListener);
    window.addEventListener('scroll', this.throttledScrollListener);
  },
  updated() {
    this.$nextTick(() => {
      this.onResize();
    });
  },
  destroyed() {
    document.body.classList.remove('vp');
    window.removeEventListener('resize', this.debouncedResizeListener);
    window.removeEventListener('scroll', this.throttledScrollListener);
  },
  methods: {
    onResize() {
      const playerHeight = window.innerWidth * (9 / 16);
      document.querySelector('html').classList.toggle('is-sticky', window.innerHeight > (playerHeight * 2));
    },
    onScroll() {
      const el = document.querySelector('.panels');
      const { top } = el.getBoundingClientRect();
      document.querySelector('html').classList.toggle('has-reached-sticky', top <= 18);
    },
    setVideoSource(url) {
      const sources = [{
        src: url,
        type: 'video/mp4',
      }];
      this.options = { ...this.options, sources };
      this.retrySources = null;
    },
    updateVideo() {
      this.setVideoSource(this.video.src);
      this.track = {
        src: `${this.datastore}videos/${this.$route.params.id}/transcript?format=vtt`,
        kind: 'captions',
        language: 'en',
        label: 'English',
        default: false,
      };
      this.fetchRelatedContent();
      this.transcript = null;
      this.transcriptLoaded = false;
    },
    setData(data) {
      this.tabIndex = 0;
      this.relatedContent = null;
      this.video = data.video;
    },
    setupObservers() {
      const stickyElm = document.querySelector('.panel--left');
      const observer = new IntersectionObserver(
        this.observerCallback,
        { threshold: [1] },
      );
      observer.observe(stickyElm);
    },
    observerCallback(e) {
      const el = document.querySelector('.panels');
      el.classList.toggle('has-reached-sticky', e[0].intersectionRatio < 1);
    },
    onPlayerError() {
      axios
        .get(`/api/video/${this.$route.params.id}/${this.$route.params.slug}`)
        .then((response) => {
          this.retrySources = [{ src: response.data.video.src, type: 'video/mp4' }];
        }).catch((err) => {
          console.error(err);
        });
    },
    fetchRelatedContent() {
      axios
        .get(`${this.datastore}videos/${this.$route.params.id}/related`)
        .then((response) => {
          this.relatedContent = response.data.data;
        }).catch((err) => {
          console.error(err);
        });
    },
    fetchTranscript() {
      axios
        .get(`${this.datastore}videos/${this.$route.params.id}/transcript?format=json`)
        .then((response) => {
          // Group word level data into paragraph level data.
          const { words, speakers } = response.data.data;
          const map = new Map(Array.from(words, (obj) => [obj.paragraphId, []]));
          words.forEach((obj) => map.get(obj.paragraphId).push(obj));
          const paragraphs = Array.from(map.values());

          this.transcript = { paragraphs, speakers };
          this.transcriptLoaded = true;
        }).catch((err) => {
          this.transcriptError = true;
          console.error(err);
        });
    },
    onTimeUpdate(value) {
      this.currentTimecode = value;
    },
    onTimecodeReset() {
      // If trying to replay a section whilst still within the
      // section, the timecode needs to be reset once set to
      // maintain reactivity.
      this.timecode = 0;
    },
    updateTimecode(value) {
      this.timecode = value;
    },
  },
};
</script>
