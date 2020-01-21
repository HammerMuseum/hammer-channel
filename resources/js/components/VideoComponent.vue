<template>
  <div class="video-wrapper">
    <video-player :options="videoOptions" :title="title"/>
    <div class="video__info">
        <div class="video-info__card">
          <div class="title"><h1>{{ title }}</h1></div>
          <div class="date">{{ new Date(date) | dateFormat('dddd, DD MMMM, YYYY') }}</div>
          <div class="description">{{ description }}</div>
          <div class="keywords">
            <ul>
              <li v-bind:key="item.id" v-for="item in tags">
                <a href="`/topics/${item}`">{{ item }}</a>
              </li>
            </ul>
          </div>
        </div>
        <button v-show="transcription" @click='toggleTranscription()'>Show/Hide transcription</button>
        <div v-show="transcriptionIsVisible" class="video-info__transcription">
          <span style="white-space: pre;">{{ transcription }}</span>
      </div>
    </div>
  </div>
</template>

<script>
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
      toggleTranscription: function(){
        this.transcriptionIsVisible = !this.transcriptionIsVisible
      }
    },
    data() {
      return {
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
      axios
        .get(`https://datastore.hammer.cogapp.com/api/videos/${this.aid}/transcript`)
        .then((response) => {
          console.log(response);
          this.transcription = response.data.data[0].transcription;
        });
    }
  }
</script>
