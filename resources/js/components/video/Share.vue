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
    <!-- Below icons are from Font Awesome -->
    <!-- https://creativecommons.org/licenses/by/4.0/ -->
      <div class="share-buttons">
        <network network="facebook">
          <svg
            title="Facebook"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-facebook" />
          </svg>
          <span class="icon-text visually-hidden">Share on Facebook</span>
        </network>
        <network network="twitter">
          <svg
            title="Twitter"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-twitter" />
          </svg>
          <span class="icon-text visually-hidden">Share on Twitter</span>
        </network>

        <div>
          <svg
            title="Citation"
            class="icon share-buttons__icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-cite" />
          </svg>
          <span class="icon-text visually-hidden">Cite</span>
          <!-- <textarea
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
          </button> -->
        </div>
      </div>
    </SocialSharing>

  </div>
</template>

<script>
import SocialSharing from 'vue-social-sharing';

export default {
  name: 'Share',
  components: {
    SocialSharing,
  },
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
