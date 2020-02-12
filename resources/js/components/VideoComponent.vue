<template>
  <div class="video-wrapper">
    <div class="breadcrumb">
      <router-link :to="{name: 'app'}">
        Back to All Videos
      </router-link>
    </div>
    <video-player
      dusk="video-player-component"
      :options="videoOptions"
      :title="title"
      @error="onPlayerError()"
    />
    <div class="video__info">
      <div class="video-info__card">
        <div class="title">
          <h1>{{ title }}</h1>
        </div>
        <div class="date">
          {{ new Date(date) | dateFormat('dddd, DD MMMM, YYYY') }}
        </div>
        <div class="description">
          <div v-if="programSeries">
            Part of:
            <router-link :to="{name: 'search', params: {params:`?facets=[1]program_series:${programSeries}`}}">
              {{ programSeries }}
            </router-link>
          </div>
          {{ description }}
        </div>
        <div class="keywords">
          <ul>
            <li
              v-for="item in keywords"
              :key="item.id"
            >
              {{ item }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import VideoPlayer from './VideoPlayer.vue';

export default {
  name: 'VideoComponent',
  components: {
    VideoPlayer,
  },
  data() {
    return {
      datastore: process.env.MIX_DATASTORE_URL,
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
      programSeries: this.programSeries
    };
  },
  watch: {
    assetId() {
      this.getTranscriptForCaptions();
    },
  },
  mounted() {
    const assetId = this.$route.params.id;
    axios
      .get(`/viewJson/${assetId}`)
      .then((response) => {
        let videoData = response.data.data;
        this.title = videoData.title;
        this.description = videoData.description;
        this.assetId = videoData.asset_id;
        this.date = videoData.date_recorded;
        this.thumbnailUrl = videoData.thumbnail_url;
        this.videoUrl = videoData.video_url;
        this.keywords = videoData.tags;
        this.programSeries = videoData.program_series;

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
    getTranscriptForCaptions() {
      axios
        .get(`${this.datastore}videos/${this.assetId}/transcript`)
        .then((response) => {
          this.transcription = response.data.data[0].transcription;
        });
    },
  },
};
</script>
