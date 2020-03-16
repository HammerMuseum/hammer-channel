<template>
  <div>
    <social-sharing :url="`${siteUrl}${fullPath}`"
      :title="title"
      :description="description"
      :quote="description"
      twitter-user="hammer_museum"
      inline-template>

        <div>
          <network network="email">
            <span class="fa fa-envelope share-button"></span> Send by Email
          </network>
          <network network="facebook">
            <span class="fa fa-facebook share-button"></span> Share on Facebook
          </network>
          <network network="twitter">
            <span class="fa fa-twitter share-button"></span> Share on Twitter
          </network>
          <network network="whatsapp">
            <span class="fa fa-whatsapp share-button"></span> Share via Whatsapp
          </network>
        </div>
    </social-sharing>
    <div class="citation">
      <label for="citation">Cite this</label>
      <textarea name="citation" id="citation" class="citation">"{{ title }}", Hammer Museum Video Archive, {{ new Date(date_recorded) | dateFormat('dddd, DD MMMM, YYYY') }}, {{siteUrl}}{{fullPath}}
      </textarea>
      <button class="copy-citation" @click="copyCitation">Copy to clipboard</button>
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
        siteUrl: 'http://hv.docker.localhost:8001'
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
