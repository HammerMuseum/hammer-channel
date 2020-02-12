<template>
  <div class="video-page-wrapper horizontal-layout">
    <div class="item-list sidebar">
      <ul>
        <li class="item-list__item" @click="toggleActivePanel($event, 'about')">About</li>
        <li class="item-list__item" @click="toggleActivePanel($event, 'share')">Share</li>
        <li class="item-list__item" @click="toggleActivePanel($event, 'use')">Use this video</li>
        <li class="item-list__item" @click="toggleActivePanel($event, 'transcript')">Transcript</li>
      </ul>
    </div>
    <div class="video-wrapper horizontal-layout">
      <!--<transition name="slide">-->
      <about
        v-show="currentPanel == 'about'"
        :description="description"
        :date="date"
        :keywords="keywords"
        :currentPanel="currentPanel"
      ></about>
      <div class="share video-wrapper__item" v-show="currentPanel == 'share'">
        <span class="close-button" @click="toggleActivePanel($event, 'share')">X</span>
        Share buttons go here.
      </div>
      <div class="use video-wrapper__item" v-show="currentPanel == 'use'">
        <span class="close-button" @click="toggleActivePanel($event, 'use')">X</span>
        Usage information goes here.
      </div>
      <div class="transcript video-wrapper__item" v-show="currentPanel == 'transcript'">
        <span class="close-button" @click="toggleActivePanel($event, 'transcript')">X</span>
        Transcript goes here.
      </div>
      <!--</transition>-->
      <video-player
        dusk="video-player-component"
        :options="videoOptions"
        :title="title"
        :keywords="keywords"
        @error="onPlayerError()"
      />
    </div>
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
    toggleActivePanel(event, name) {
      let clickedElem = event.target;
      if (this.currentPanel === name) {
        this.currentPanel = null;
        clickedElem.classList.remove('active-panel');
        document.querySelector('.video-info--title').classList.remove('hidden');
      } else {
        this.currentPanel = name;
        clickedElem.classList.add('active-panel');
        document.querySelector('.video-info--title').classList.add('hidden');
      }
    },
    leave() {
      let videoContainer = document.querySelector('.hammer-video-player');
      if (!videoContainer.classList.contains('resize-video')) {
        videoContainer.classList.add('resize-video');
      }
    },
    afterLeave() {
      let videoContainer = document.querySelector('.hammer-video-player');
      videoContainer.classList.remove('resize-video');
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
