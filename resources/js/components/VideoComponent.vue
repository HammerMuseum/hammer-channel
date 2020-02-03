<template>
  <div class="video-wrapper">
    <div class="breadcrumb">
      <router-link :to="{name: 'app'}">Back to All Videos</router-link>
    </div>
    <video-player dusk="video-player-component"
       :options="this.videoOptions"
       :title="title"
       @error="onPlayerError()">
    </video-player>
    <div class="video__info">
        <div class="video-info__card">
          <div class="title"><h1>{{ title }}</h1></div>
          <div class="date">{{ new Date(this.date) | dateFormat('dddd, DD MMMM, YYYY') }}</div>
          <div class="description">{{ this.description }}</div>
          <div class="keywords">
            <ul>
              <li v-bind:key="item.id" v-for="item in this.keywords">
                {{ item }}
                <!--<a :href="`/topics/${item}`">{{ item }}</a>-->
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
        title: this.title,
        description: this.description,
        date: this.date,
        transcriptionIsVisible: false,
        transcription: null,
        videoUrl: this.videoUrl,
        keywords: this.keywords,
        videoOptions: this.videoOptions
      };
    },
    mounted() {
        var asset_id = this.$route.params.id;
        axios
            .get(`/viewJson/${asset_id}`)
            .then((response) => {
              console.log(response);
                this.title = response.data.data.title;
                this.description = response.data.data.description;
                this.asset_id = response.data.data.asset_id;
                this.date = response.data.data.date_recorded;
                this.thumbnail_url = response.data.data.thumbnail_url;
                this.videoUrl = response.data.data.video_url;
                this.keywords = response.data.data.tags;

                this.videoOptions = {
                    autoplay: false,
                        controls: true,
                        sources: [
                        {
                            src: this.videoUrl,
                            type: "video/mp4"
                        }
                    ]
                }
            });


      // axios
        // .get(`https://datastore.hammer.cogapp.com/api/videos/${this.aid}/transcript`)
        // .then((response) => {
        //   this.transcription = response.data.data[0].transcription;
        // });
    }
  }
</script>
