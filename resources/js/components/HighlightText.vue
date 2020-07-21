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
          <div class="input__wrapper">
            <VInput
              ref="input"
              v-model="query"
              :classes="{
                input: ['form__input', 'form__input--search'],
                text: 'visually-hidden'
              }"
              type="text"
              label="Search the transcript"
              name="searchTranscript"
              placeholder="Search"
            />
          </div>
          <div class="highlighter__actions">
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
              @click="clearHandler(); $emit('toggle-highlighter')"
            >
              <span class="visually-hidden">Close transcript search</span>
              <SvgIcon
                name="close"
                title="Close transcript search"
              />
            </button>
          </div>
        </div>
        <div class="highlighter__summary">
          <div v-if="previousSearch">
            <transition name="fade">
              <button
                class="button button--action"
                @click="handleUsePreviousSearch"
              >
                {{ `Use your search query: "${previousSearch}"?` }}
              </button>
            </transition>
          </div>
          <span v-show="query && query.length >= 3">
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
import { VInput } from 'vuetensils/src/components';
import Mark from 'mark.js';
import SvgIcon from './SvgIcon.vue';
import { store } from '../store';

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
      currentIndex: 0,
      hits: [],
      isActive: false,
      markInstance: null,
      options: {
        className: 'ht',
        element: 'span',
        separateWordSearch: true,
        ignorePunctuation: ":;.,-–—‒_(){}[]!'\"+=".split(''),
      },
      previousSearch: null,
      query: null,
    };
  },
  computed: {
    searchSummary() {
      if (!this.hits.length) return 'No results';
      const num = this.hits.length;
      const current = this.currentIndex + 1;
      const results = num > 1 ? 'results' : 'result';
      return `${current} of ${num} ${results}`;
    },
    searchTerm() {
      return store.searchTerm;
    },
  },
  watch: {
    query() {
      this.highlight();
    },
  },
  mounted() {
    this.markInstance = new Mark(this.$refs.context);
    this.previousSearch = this.searchTerm;
  },
  methods: {
    handleUsePreviousSearch() {
      if (this.previousSearch) {
        this.query = this.previousSearch;
        this.previousSearch = null;
      }
    },
    handleEnter() {
      this.$nextTick(() => {
        this.$refs.input.$refs.input.focus();
      });
    },
    clearHandler() {
      this.query = null;
      this.hits.forEach((el) => {
        el.classList.remove('current');
      });
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
      if (this.query && this.query.length >= 3) {
        const self = this;
        this.markInstance.unmark({
          done() {
            self.markInstance.mark(self.query, {
              ...self.options,
              done() {
                self.hits = self.$refs.context.querySelectorAll('span.ht');
                self.currentIndex = 0;
                if (self.hits.length) {
                  self.hits[0].classList.add('current');
                  self.$emit('scroll-to', self.hits[0]);
                } else {
                  self.hits = [];
                }
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
          this.$emit('scroll-to', current);
        }
      }
    },
  },
};
</script>
