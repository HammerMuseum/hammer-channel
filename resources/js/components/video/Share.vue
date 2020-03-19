<template>
  <div>
    <social-sharing :url="`${siteUrl}${fullPath}`"
      :title="title"
      :description="description"
      quote="Watch this on the Hammer Video Archive"
      twitter-user="hammer_museum"
      inline-template>

      <!-- These icons come from font awesome and need attribution -->

        <div class="share-buttons">
          <network network="email">
            <span class="fa fa-envelope share-button"></span>
            <img class="share-buttons__icon" src="/assets/images/icons/envelope.svg">
          </network>
          <network network="facebook">
            <span class="fa fa-facebook share-button"></span>
            <img class="share-buttons__icon" src="/assets/images/icons/facebook.svg">
          </network>
          <network network="twitter">
            <span class="fa fa-twitter share-button"></span>
            <img class="share-buttons__icon" src="/assets/images/icons/twitter.svg">
          </network>
          <network network="whatsapp">
            <span class="fa fa-whatsapp share-button"></span>
            <img class="share-buttons__icon" src="/assets/images/icons/whatsapp-brands.svg">
          </network>
        </div>
    </social-sharing>
    <div class="citation">
      <label for="citation" class="citation__label">Cite this</label>
      <textarea name="citation" id="citation" class="citation__content">"{{ title }}", Hammer Museum Video Archive, recorded {{ new Date(date_recorded) | dateFormat('dddd, DD MMMM, YYYY') }}, {{siteUrl}}{{fullPath}}
      </textarea>
      <button class="citation__copy" @click="copyCitation">Copy to clipboard</button>
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
      title_slug: {
        type: String,
        default: ''
      },
      date_recorded: {
        type: String,
        default: ''
      }
    },
    data() {
      return {
        fullPath: this.$route.path,
        siteUrl: process.env.MIX_APP_URL
      }
    },
    methods: {
      copyCitation() {
        const citation = document.querySelector('[name=citation]');
        console.log(citation);
        citation.select();
        document.execCommand('copy');
      },
    }
  };
</script>
