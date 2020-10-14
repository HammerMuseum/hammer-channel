<template>
  <div class="container">
    <div
      v-if="video"
      id="start-of-content"
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
        >
          <span>Original program date: </span>
          <span class="video-meta__date">{{ new Date(video.date_recorded) | dateFormat('MMM D, YYYY') }}</span>
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
            :clip-start="clipStart"
            :clip-end="clipEnd"
            :has-active-clip="isClip && isValidClip"
            @loadedmetadata="onLoadedMetadata"
            @playbackerror="onPlayerError"
            @timeupdate="onTimeUpdate"
            @timecode-reset="onTimecodeReset"
            @remove-clip="onRemoveClip"
          />
        </div>
        <div class="panel--right">
          <BTabs
            v-model="tabIndex"
            class="vp__tabs"
            lazy
          >
            <BTab
              active
              @click="jumpToLowerPanel"
            >
              <template v-slot:title>
                <BaseIcon
                  width="18"
                  height="18"
                  view-box="0 0 192 512"
                  icon-name="info"
                  title="Information about the video"
                >
                  <InfoIcon />
                </BaseIcon>
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
              @click="jumpToLowerPanel"
            >
              <template v-slot:title>
                <BaseIcon
                  width="18"
                  height="18"
                  view-box="0 0 18 18"
                  icon-name="transcript"
                  title="View transcript"
                >
                  <TranscriptIcon />
                </BaseIcon>
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

            <BTab @click="jumpToLowerPanel">
              <template v-slot:title>
                <BaseIcon
                  width="18"
                  height="18"
                  view-box="0 0 24 17"
                  icon-name="clip"
                  title="Clip this video"
                >
                  <ClipIcon />
                </BaseIcon>
                <h3 :class="['vp__tabs__label', {'vp__tabs__label--notify': isClip}] ">
                  Clip
                </h3>
              </template>
              <ClippingTool
                :clip-start="clipStart"
                :clip-end="clipEnd"
                :has-active-clip="isClip && isValidClip"
                :current-timecode="currentTimecode"
                @update-clip="onUpdateClip"
                @remove-clip="onRemoveClip"
              />
            </BTab>

            <BTab @click="jumpToLowerPanel">
              <template v-slot:title>
                <BaseIcon
                  width="18"
                  height="18"
                  view-box="0 0 18 18"
                  icon-name="share"
                  title="Share the video"
                >
                  <ShareIcon />
                </BaseIcon>
                <h3 class="vp__tabs__label">
                  Share
                </h3>
              </template>
              <Share
                :title="video.title"
                :date="video.date_recorded"
                :duration="video.duration"
              />
            </BTab>

            <BTab @click="jumpToLowerPanel">
              <template v-slot:title>
                <BaseIcon
                  width="18"
                  height="18"
                  view-box="0 0 18 18"
                  icon-name="related"
                  title="Related videos"
                >
                  <RelatedIcon />
                </BaseIcon>
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
      clipStart: null,
      clipEnd: null,
      currentTimecode: 0,
      datastore: process.env.MIX_DATASTORE_URL,
      debouncedResizeObserver: null,
      duration: null,
      hasReachedSticky: false,
      isSticky: false,
      isClip: false,
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
    isValidClip() {
      const start = this.clipStart;
      const end = this.clipEnd;
      if (this.duration === null || (Number.isNaN(end) || Number.isNaN(start)) || start === 0 || end < start || end > this.duration) {
        return false;
      }
      return true;
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
      return `/images/d/large/${video.thumbnailId}.jpg`;
    },
    transcriptInit() {
      return store.transcriptInit;
    },
  },
  watch: {
    '$route.query.start': function () {
      this.checkRouteClipStatus();
    },
    '$route.query.end': function () {
      this.checkRouteClipStatus();
    },
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
    if (to.path !== from.path) {
      axios.get(`/api${to.path}`).then(({ data }) => {
        this.setData(data);
        next();
      });
    } else {
      next();
    }
  },
  mounted() {
    document.body.classList.add('vp');
    this.debouncedResizeListener = debounce(this.onResize, 1000);
    this.throttledScrollListener = throttle(this.onScroll, 100);
    window.addEventListener('resize', this.debouncedResizeListener);
    window.addEventListener('scroll', this.throttledScrollListener);
    this.checkRouteClipStatus();
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
    jumpToLowerPanel() {
      if (this.isSticky && !this.hasReachedSticky) {
        window.scrollTo(0, this.$refs.videoPlayer.offsetTop + 24);
      }
    },
    onUpdateClip(start, end) {
      this.clipStart = start;
      if (end < this.duration) {
        this.clipEnd = end;
      }
    },
    onRemoveClip() {
      this.$router.push({ path: this.$route.path }).catch();
    },
    checkRouteClipStatus() {
      const query = this.$route.query;

      if (Object.prototype.hasOwnProperty.call(query, 'start')) {
        this.isClip = true;
        const start = parseInt(query.start, 10);
        this.clipStart = start;

        if (Object.prototype.hasOwnProperty.call(query, 'end')) {
          const end = parseInt(query.end, 10);

          if (end > start) {
            this.clipEnd = end;
          }
        }
      } else {
        this.isClip = false;
      }
    },
    onLoadedMetadata(player) {
      this.duration = Math.ceil(parseInt(player.duration(), 10));
      if (this.clipEnd > this.duration) {
        this.clipEnd = null;
      }
    },
    onResize() {
      const playerHeight = window.innerWidth * (9 / 16);
      const condition = window.innerHeight > (playerHeight * 2);
      document.querySelector('html').classList.toggle('is-sticky', condition);
      this.isSticky = condition;
    },
    onScroll() {
      const el = document.querySelector('.panels');
      const { top } = el.getBoundingClientRect();
      const condition = top <= 18;
      document.querySelector('html').classList.toggle('has-reached-sticky', condition);
      this.hasReachedSticky = condition;
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
      const condition = e[0].intersectionRatio < 1;
      el.classList.toggle('has-reached-sticky', condition);
      this.hasReachedSticky = condition;
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
          const { words, speakers } = response.data;
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
