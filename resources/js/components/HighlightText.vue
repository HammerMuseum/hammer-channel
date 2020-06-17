<template>
  <div class="highlighter">
    <transition
      name="fade"
      @enter="handleEnter"
    >
      <div
        v-show="showHighlighter"
        class="highlighter__inner"
      >
        <div
          class="highlighter__controls"
        >
          <input
            ref="input"
            v-model="query"
            label="Search the transcript"
            name="searchTranscript"
            class="form__input"
          >
          <button
            class="button button--action"
            @click="prevHandler"
          >
            Prev
          </button>
          <button
            class="button button--action"
            @click="nextHandler"
          >
            Next
          </button>
          <button
            class="button button--action"
            @click="clearHandler"
          >
            Clear
          </button>
          <button
            class="button button--icon"
            @click="$emit('close-highlighter')"
          >
            <span class="visually-hidden">Close transcript search</span>
            <SvgIcon
              name="close"
              title="Close transcript search"
            />
          </button>
        </div>
        <div class="highlighter__summary">
          <span v-show="hits.length">
            {{ searchSummary }}
          </span>
        </div>
      </div>
    </transition>
    <div
      ref="context"
      :class="[
        'highlighter__context',
        {'highlighter__context--active': showHighlighter}
      ]"
    >
      <slot />
    </div>
  </div>
</template>

<script>
import Mark from 'mark.js';
import VueScrollTo from 'vue-scrollto';
import { VInput } from 'vuetensils/src/components';
import SvgIcon from './SvgIcon.vue';

export default {
  components: {
    SvgIcon,
    VInput,
  },
  props: {
    showHighlighter: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      isActive: false,
      currentIndex: 0,
      hits: [],
      markInstance: null,
      query: '',
      options: {
        className: 'ht',
        element: 'span',
      },
    };
  },
  computed: {
    searchSummary() {
      const num = this.hits.length;
      const current = this.currentIndex + 1;
      const results = num > 1 ? 'results' : 'result';
      return `${current} of ${num} ${results}`;
    },
  },
  watch: {
    query() {
      this.highlight();
    },
  },
  mounted() {
    this.markInstance = new Mark(this.$refs.context);
  },
  methods: {
    handleEnter() {
      this.$refs.input.focus();
    },
    clearHandler() {
      this.query = '';
      this.hits = [];
    },
    nextHandler() {
      this.currentIndex += 1;
      if (this.currentIndex > this.hits.length - 1) {
        this.currentIndex = 0;
      }
      this.jumpTo();
    },
    prevHandler() {
      this.currentIndex -= 1;
      if (this.currentIndex === -1) {
        this.currentIndex = this.hits.length - 1;
      }
      this.jumpTo();
    },
    highlight() {
      if (this.query.length >= 3) {
        const self = this;
        this.markInstance.unmark({
          done() {
            self.markInstance.mark(self.query, {
              ...self.options,
              done() {
                self.hits = self.$refs.context.querySelectorAll('span.ht');
                self.currentIndex = 0;
              },
            });
          },
        });
      } else {
        this.markInstance.unmark();
      }
    },
    jumpTo() {
      if (this.hits.length) {
        const current = this.hits[this.currentIndex];
        this.hits.forEach((el) => {
          el.classList.remove('current');
        });
        if (current) {
          current.classList.add('current');
          VueScrollTo.scrollTo(current, 400, { container: '.tab--transcript .video-meta__inner', offset: -120 });
        }
      }
    },
  },
};
</script>
