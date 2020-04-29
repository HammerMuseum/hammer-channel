<template>
  <div>
    <SocialSharing
      :url="`${siteUrl}${fullPath}`"
      :title="title"
      :description="description"
      quote="Watch this on the Hammer Video Archive"
      twitter-user="hammer_museum"
      inline-template
    >
      <!-- These icons come from font awesome and need attribution -->

      <div class="share-buttons">
        <Network network="email">
          <span class="fa fa-envelope share-button" />
          <svg
            title="Previous"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-envelope" />
          </svg>
          <span class="icon-text visually-hidden">Email</span>
        </Network>
        <Network network="facebook">
          <span class="fa fa-facebook share-button" />
          <svg
            title="Previous"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-facebook" />
          </svg>
          <span class="icon-text visually-hidden">Share on Facebook</span>
        </Network>
        <Network network="twitter">
          <span class="fa fa-twitter share-button" />
          <svg
            title="Previous"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-twitter" />
          </svg>
          <span class="icon-text visually-hidden">Share on Twitter</span>
        </Network>
        <Network network="whatsapp">
          <span class="fa fa-whatsapp share-button" />
          <svg
            title="Previous"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-whatsapp" />
          </svg>
          <span class="icon-text visually-hidden">Share on whatsapp</span>
        </Network>
      </div>
    </SocialSharing>
    <div class="citation">
      <label
        for="citation"
        class="citation__label"
      >Cite this</label>
      <textarea
        id="citation"
        name="citation"
        class="citation__content"
      >"{{ title }}", Hammer Museum Video Archive, recorded {{ new Date(dateRecorded) | dateFormat('dddd, DD MMMM, YYYY') }}, {{ siteUrl }}{{ fullPath }}
      </textarea>
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
  name: 'Share',
  props: {
    title: {
      type: String,
      default: '',
    },
    description: {
      type: String,
      default: '',
    },
    titleSlug: {
      type: String,
      default: '',
    },
    dateRecorded: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      fullPath: this.$route.path,
      siteUrl: process.env.MIX_APP_URL,
    };
  },
  methods: {
    copyCitation() {
      const citation = document.querySelector('[name=citation]');
      citation.select();
      document.execCommand('copy');
    },
  },
};
</script>
