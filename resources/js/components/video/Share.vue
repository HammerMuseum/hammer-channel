<template>
  <VideoMeta>
    <template v-slot:highlighted>
      <div class="share-buttons">
        <a
          class="share-button button button--icon"
          :href="facebook"
          target="blank"
          title="Share on Facebook"
        >
          <svg
            title="Facebook"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-facebook" />
          </svg>
        </a>
        <a
          class="share-button button button--icon"
          :href="twitter"
          target="blank"
          title="Share on Twitter"
        >
          <svg
            title="Twitter"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-twitter" />
          </svg>
        </a>
        <button
          class="share-button button button--icon"
          aria-label="Show the video citation"
          title="Get citation"
          @click="showCitation = !showCitation"
        >
          <svg
            title="Citation"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-cite" />
          </svg>
          <span class="icon-text visually-hidden">Cite this video</span>
        </button>
      </div>
    </template>
    <transition name="fade">
      <div
        v-show="showCitation"
        class="citation"
      >
        <h4 class="video-meta__label">
          Citation
        </h4>
        <p
          id="citation"
          name="citation"
          class="citation__content"
        >
          {{ citation }}
        </p>
        <div class="citation__copy">
          <button
            :class="['button', 'button--action']"
            aria-label="Copy citation to clipboard"
            @click="copyToClipboard(citation)"
          >
            <transition
              name="fade"
              mode="out-in"
              :duration="100"
            >
              <span :key="copied">{{ copied ? 'Copied' : 'Copy citation to clipboard' }}</span>
            </transition>
          </button>
        </div>
      </div>
    </transition>
  </VideoMeta>
</template>

<script>
import VideoMeta from '../VideoMeta.vue';
import CopyTo from '../../mixins/copyToClipboard';

export default {
  name: 'Share',
  components: {
    VideoMeta,
  },
  mixins: [CopyTo],
  props: {
    date: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      providers: {
        twitter: 'https://twitter.com/intent/tweet/?url=:url&text=:text',
        facebook: 'https://www.facebook.com/sharer/sharer.php?u=:u&title=:title',
      },
      showCitation: false,
    };
  },
  computed: {
    dateFormatted() {
      return this.$options.filters.dateFormat(new Date(this.date), 'dddd DD MMMM, YYYY');
    },
    name() {
      return 'Hammer Museum Video Archive';
    },
    text() {
      return this.title;
    },
    url() {
      return window.location.href;
    },
    citation() {
      return `"${this.title}", ${this.name}, recorded ${this.dateFormatted}, ${this.url}`;
    },
    facebook() {
      return this.providers.facebook.replace(':u', encodeURIComponent(this.url)).replace(':title', encodeURIComponent(this.text));
    },
    twitter() {
      return this.providers.twitter.replace(':url', encodeURIComponent(this.url)).replace(':text', encodeURIComponent(this.text));
    },
  },
};
</script>
