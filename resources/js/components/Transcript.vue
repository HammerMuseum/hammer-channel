<template>
  <VideoMeta>
    <template v-slot:highlighted>
      <div
        class="video-meta__transcript"
      >
        <template v-if="items.length">
          <div class="transcript__options">
            <button
              class="button button--action highlighter-toggle"
              aria-haspopup="true"
              :aria-expanded="highlightControlsActive ? 'true' : 'false'"
              @click="handleHighlighterOpen"
            >
              <span class="visually-hidden">Search within the transcript</span>
              <SvgIcon
                name="search"
                title="Search within the transcript"
              />
            </button>
            <button
              class="button button--action"
              @click="initDownload"
            >
              <span class="download__title">Download transcript</span>
            </button>
          </div>
          <HighlightText
            :show-highlighter="highlightControlsActive"
            @toggle-highlighter="highlightControlsActive = !highlightControlsActive"
            @scroll-to="handleHighlighterScroll"
          >
            <div
              class="transcript__content"
              tabindex="0"
            >
              <span
                id="transcript-anchor"
                class="visually-hidden"
              >Transcript content</span>
              <p
                v-for="item in items"
                :key="item.id"
                class="transcript__paragraph"
                :class="{
                  'transcript__paragraph--active': isActive(item.start, item.end, item.id)
                }"
              >
                <VTooltip
                  focus
                  tag="div"
                  :classes="{ toggle: 'tooltip--transcript', content: 'tooltip__content' }"
                >
                  <template #tooltip>
                    <button
                      :aria-label="`Go to ${item.timecode}`"
                      class="button button--light"
                      @mousedown="handleTranscriptClick(item.start)"
                    >
                      <SvgIcon
                        name="play"
                        :title="`Play from ${item.timecode}`"
                      />
                      <time>{{ item.timecode }}</time>
                    </button>
                  </template>
                  {{ item.message }}
                </VTooltip>
              </p>
            </div>
          </HighlightText>
        </template>
        <template
          v-else-if="error"
        >
          Sorry, the transcript for this video failed to load correctly.
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

        <BackToTop
          label="Go to top of transcript"
          :container="transcriptScrollContainer"
          @scroll-top="handleBackToTopScroll"
        >
          <span class="visually-hidden">Go to top of transcript</span>
          <SvgIcon
            name="next"
            title="Go to top of transcript"
          />
        </BackToTop>
      </div>
    </template>
  </VideoMeta>
</template>

<script>
import { saveAs } from 'file-saver';
import scrollIntoView from 'scroll-into-view';
import { vueWindowSizeMixin } from 'vue-window-size';
import { VTooltip } from 'vuetensils/src/components';
import SvgIcon from './SvgIcon.vue';
import HighlightText from './HighlightText.vue';
import BackToTop from './BackToTop.vue';
import { store, mutations } from '../store';

export default {
  name: 'Transcript',
  components: {
    BackToTop,
    HighlightText,
    SvgIcon,
    VTooltip,
  },
  mixins: [vueWindowSizeMixin],
  props: {
    currentTimecode: {
      type: Number,
      default() {
        return 0;
      },
    },
    error: {
      type: Boolean,
      default: false,
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
      highlightControlsActive: false,
    };
  },
  computed: {
    clean() {
      return store.transcriptInit;
    },
    highlighterOffset() {
      return this.windowWidth > 960 ? -120 : ((window.innerHeight / 2) + 40) * -1;
    },
    transcriptHasLoaded() {
      return this.items.length;
    },
    transcriptScrollContainer() {
      if (this.windowWidth > 960) {
        return '.tab--transcript .video-meta__inner';
      }
      return null;
    },
  },
  mounted() {
    this.toggleTranscriptInit();
  },
  destroyed() {
    this.toggleTranscriptInit();
  },
  methods: {
    handleHighlighterOpen() {
      this.highlightControlsActive = !this.highlightControlsActive;
    },
    handleHighlighterScroll(el) {
      this.handleScrollTo(el);
    },
    handleScrollTo(el) {
      const width = this.windowWidth;
      scrollIntoView(el, {
        time: 0,
        align: {
          top: 0.75,
          topOffset: 0,
        },
        validTarget(target) {
          return width > 960 ? target !== window : true;
        },
      });
    },
    handleBackToTopScroll() {
      this.handleScrollTo(document.querySelector('#transcript-anchor'));
    },
    handleTranscriptClick(timecode) {
      this.$emit('update-timecode', timecode);
    },
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
