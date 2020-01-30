<template>
  <div class="video-wrapper">
    <video-player dusk="video-player-component"
       :options="videoOptions"
       :title="title"
       :trackList="trackList"
       @error="onPlayerError()">
    </video-player>
    <div class="video__info">
        <div class="video-info__card">
          <div class="title"><h1>{{ this.title }}</h1></div>
          <div class="date">{{ new Date(this.date) | dateFormat('dddd, DD MMMM, YYYY') }}</div>
          <div class="description">{{ this.description }}</div>
          <div class="keywords">
            <ul>
              <li v-bind:key="item.id" v-for="item in this.tags">
                <a :href="`/topics/${item}`">{{ item }}</a>
              </li>
            </ul>
          </div>
        </div>
        <button v-show="transcription" @click='toggleTranscription()'>Show/Hide transcription</button>
        <div v-show="transcriptionIsVisible" class="video-info__transcription">
          <span style="white-space: pre;">{{ this.transcription }}</span>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import VideoPlayer from "./VideoPlayer.vue";
  export default {
    name: 'VideoComponent',
    props: {
      url: String,
      aid: String,
      title: String,
      date: String,
      description: String,
      tags: Array,
    },
    components: {
      VideoPlayer
    },
    methods:{
      toggleTranscription() {
        this.transcriptionIsVisible = !this.transcriptionIsVisible
      }
    },
    data() {
      return {
        videoTitle: this.title,
        transcriptionIsVisible: false,
        transcription: null,
        videoOptions: {
          autoplay: false,
          controls: true,
          sources: [
            {
              src: this.url,
              type: "video/mp4"
            }
          ]
        }
      };
    },
    mounted() {
        var asset_id = this.$route.params.id;
        axios
            .get(`http://video.rufio.office.cogapp.com/viewJson/${asset_id}`)
            .then((response) => {
                console.log(response)
                this.title = response.data.state.data.title;
                this.description = response.data.state.data.description;
                this.asset_id = response.data.state.data.asset_id;
                this.date = response.data.state.data.date_recorded;
                this.thumbnail_url = response.data.state.data.thumbnail_url;
                this.videos = response.data.state.data.videos;
            });

      axios
        .get(`https://datastore.hammer.cogapp.com/api/videos/${this.aid}/transcript`)
        .then((response) => {
          this.transcription = response.data.data[0].transcription;
        });
    }
  }
</script>
