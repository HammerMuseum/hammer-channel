<template>
  <VideoMeta>
    <template v-slot:highlighted>
      <div
        class="video-meta__transcript"
      >
        <template v-if="items.length">
          <div class="transcript__actions">
            <div class="transcript__download">
              <a
                href="#"
                class="download__link link link--text link--text-secondary"
                @click.prevent="initDownload"
              >
                <span class="download__title">Download transcript</span>
              </a>
            </div>
          </div>

          <div
            class="transcript__content"
            tabindex="0"
          >
            <span class="visually-hidden">Transcript content</span>
            <p
              v-for="item in items"
              :key="item.id"
              class="transcript__paragraph"
              :class="{ 'transcript__paragraph--active': isActive(item.start, item.end, item.id)}"
            >
              {{ item.message }}
            </p>
          </div>
        </template>
        <template
          v-else
        >
          <div class="loading-icon">
            <svg
              viewBox="0 0 60 60"
            >
              <path
                d="M 0 0 H 60 V 60 H 0 Z"
                fill="transparent"
                stroke="white"
                stroke-width="4"
                class="svg-square-animate"
              />
            </svg>
          </div>
        </template>

        <ScrollToTop
          label="Go to top of transcript"
          :element="transcriptScrollContainer"
          :container="transcriptScrollContainer"
        >
          <span class="visually-hidden">Go to top of transcript</span>
          <SvgIcon
            name="next"
            title="Go to top of transcript"
          />
        </ScrollToTop>
      </div>
    </template>
  </VideoMeta>
</template>

<script>
import { saveAs } from 'file-saver';
import { vueWindowSizeMixin } from 'vue-window-size';
import VueScrollTo from 'vue-scrollto';
import ScrollToTop from './ScrollToTop.vue';
import { store, mutations } from '../store';

export default {
  name: 'Transcript',
  components: {
    ScrollToTop,
  },
  mixins: [vueWindowSizeMixin],
  props: {
    currentTimecode: {
      type: Number,
      default() {
        return 0;
      },
    },
    items: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      currentPara: null,
      scrollInProgress: false,
    };
  },
  computed: {
    clean() {
      return store.transcriptInit;
    },
    transcriptHasLoaded() {
      return this.items.length;
    },
    transcriptScrollContainer() {
      return this.windowWidth > 960 ? '.tab--transcript .video-meta__inner' : undefined;
    },
  },
  watch: {
    currentPara() {
      const self = this;
      const options = {
        container: '.tab--transcript',
        easing: 'ease-in',
        offset: -200,
        force: true,
        onStart() {
          self.scrollInProgress = true;
        },
        onDone() {
          self.scrollInProgress = false;
        },
        x: false,
        y: true,
      };
      const el = this.$el.getElementsByClassName('transcript__paragraph--active')[0];
      VueScrollTo.scrollTo(el, 1200, options);
    },
  },
  mounted() {
    this.toggleTranscriptInit();
  },
  destroyed() {
    this.toggleTranscriptInit();
  },
  methods: {
    initDownload() {
      const output = this.items.map((el) => `${el.message}${'\r\n\r\n'}`);
      const blob = new Blob(output, { type: 'text/plain;charset=utf-8' });
      saveAs(blob, 'transcript.txt');
    },
    isActive(start, end, paraId) {
      if (this.currentTimecode >= start && this.currentTimecode <= end) {
        this.currentPara = paraId;
        return true;
      }
      return false;
    },
    toggleTranscriptInit: mutations.toggleTranscriptInit,
  },
};
</script>
