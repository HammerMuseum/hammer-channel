<template>
  <div class="share-buttons__wrapper">
    <div class="share-buttons">
      <a
        class="share-button button button--icon"
        @click.prevent="share(facebook)"
      >
        <svg
          title="Facebook"
          class="icon share-buttons__icon"
        >
          <use xlink:href="/images/sprite.svg#sprite-facebook" />
        </svg>
        <span class="icon-text visually-hidden">Share on Facebook</span>
      </a>
      <a
        class="share-button button button--icon"
        @click.prevent="share(twitter)"
      >
        <svg
          title="Twitter"
          class="icon share-buttons__icon"
        >
          <use xlink:href="/images/sprite.svg#sprite-twitter" />
        </svg>
        <span class="icon-text visually-hidden">Share on Twitter</span>
      </a>
      <div class="share-button">
        <button
          class="share-button button button--icon"
          aria-label="Show citation"
          @click="showCitation = !showCitation;"
        >
          <svg
            title="Citation"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-cite" />
          </svg>
        </button>
      </div>
    </div>
    <div
      v-show="showCitation"
      class="citation"
    >
      <p
        id="citation"
        name="citation"
        class="citation__content"
      >
        {{ citation }}
      </p>
      <button
        class="citation__copy"
        @click="copyCitation"
      >
        Copy to clipboard
      </button>
    </div>
  </div>
</template>

<script>
export default {
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
      name: 'Hammer Museum Video Archive',
      url: window.location.href,
      providers: {
        twitter: 'https://twitter.com/intent/tweet/?url=:url&text=:text',
        facebook: 'https://www.facebook.com/sharer/sharer.php?u=:u&title=:title',
      },
      showCitation: false,
    };
  },
  computed: {
    formattedDate() {
      return this.$options.filters.dateFormat(new Date(this.date), 'dddd, DD MMMM, YYYY');
    },
    text() {
      return this.title;
    },
    citation() {
      return `"${this.title}", ${this.name}, recorded ${this.date}, ${this.url}`;
    },
    facebook() {
      return this.providers.facebook.replace(':u', this.url).replace(':title', this.text);
    },
    twitter() {
      return this.providers.twitter.replace(':url', this.url).replace(':text', this.text);
    },
  },
  methods: {
    copyCitation() {
      const citation = document.querySelector('[name=citation]');
      citation.select();
      document.execCommand('copy');
    },
    share(url) {
      window.open(url, 'sharer', 'toolbar=0,status=0,width=648,height=395');
      return true;
    },
  },
};
</script>
