<template>
  <div class="video-wrapper horizontal-layout">
    <div class="item-list sidebar">
      <ul>
        <li @click="setCurrentPanel('about')">About</li>
      </ul>
    </div>
    <!--<transition name="slide">-->
      <about
        v-show="currentPanel == 'about'"
        :description="description"
        :date="date"
        :keywords="keywords"
        :currentPanel="currentPanel"
      ></about>
    <!--</transition>-->
    <video-player
      dusk="video-player-component"
      :options="videoOptions"
      :title="title"
      @error="onPlayerError()"
    />
  </div>
</template>

<script>
import axios from 'axios';
import VideoPlayer from './VideoPlayer.vue';
import About from './AboutComponent.vue';

export default {
  name: 'VideoComponent',
  components: {
    VideoPlayer,
    About
  },
  data() {
    return {
      datastore: /*process.env.MIX_DATASTORE_URL,*/ 'http://datastore.rufio.office.cogapp.com/api/',
      assetId: null,
      title: this.title,
      description: this.description,
      date: this.date,
      transcriptionIsVisible: false,
      transcription: null,
      videoUrl: this.videoUrl,
      thumbnailUrl: null,
      keywords: this.keywords,
      videoOptions: this.videoOptions,
      currentPanel: null
    };
  },
  watch: {
    // assetId() {
    //   this.getTranscriptForCaptions();
    // },
  },
  mounted() {
    const assetId = this.$route.params.id;
    axios
      .get(`/viewJson/${assetId}`)
      .then((response) => {
        this.title = response.data.data.title;
        this.description = response.data.data.description;
        this.assetId = response.data.data.asset_id;
        this.date = response.data.data.date_recorded;
        this.thumbnailUrl = response.data.data.thumbnail_url;
        this.videoUrl = response.data.data.video_url;
        this.keywords = response.data.data.tags;

        this.videoOptions = {
          autoplay: false,
          controls: true,
          sources: [
            {
              src: this.videoUrl,
              type: 'video/mp4',
            },
          ],
        };
      });
  },
  methods: {
    onPlayerError() {
      axios
        .get(`/viewJson/${this.$route.params.id}`)
        .then((response) => {
          this.videoUrl = response.data.data.video_url;
        });
    },
    setCurrentPanel(name) {
      if (this.currentPanel == name) {
        this.currentPanel = null;
      } else {
        this.currentPanel = name;
      }
    }
/*    getTranscriptForCaptions() {
      axios
        .get(`${this.datastore}videos/${this.assetId}/transcript`)
        .then((response) => {
          this.transcription = response.data.data[0].transcription;
        });
    },*/
  },
};
</script>
