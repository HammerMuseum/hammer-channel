<template>
  <div class="listing">
    <router-link :to="{name: 'search'}">
      Search
    </router-link>
    <h1 class="title">
      {{ title }}
    </h1>
    <div class="topics">
      <ul>
        <li v-for="(videos, topic) in topics" :key="topic">
          {{topic}}
        </li>
      </ul>
    </div>


    <div class="">
      <VueSlickCarousel v-bind="settings" v-for="(topic, topic_name) in topics" :key="topic_name" :dots="true" :arrows="true">
        {{ topic_name }}
        <div v-for="video in topic">
            <div>
              <img :src="video._source['thumbnail_url']">
            </div>
        </div>
        <div class="video">
          See all
        </div>
      </VueSlickCarousel>
    </div>





    <!--<result-grid :videos="videos" />-->
    <!--<div class="pager">-->
      <!--<ul>-->
        <!--<li-->
          <!--v-for="(item, index) in pager"-->
          <!--:key="item"-->
        <!--&gt;-->
          <!--<router-link-->
            <!--:to="{name: 'app'}"-->
            <!--@click.native="getPageData(item)"-->
          <!--&gt;-->
            <!--{{ index }}-->
          <!--</router-link>-->
        <!--</li>-->
      <!--</ul>-->
    <!--</div>-->
  </div>
</template>

<script>
import axios from 'axios';
import ResultGrid from './ResultGridComponent.vue';
import mixin from '../mixin';
import VueSlickCarousel from 'vue-slick-carousel'
// optional style for arrows & dots


export default {
  name: 'Home',
  components: {
    ResultGrid,
    VueSlickCarousel
  },
  mixins: [mixin],
  data() {
    return {
      title: this.title,
      videos: this.videos,
      pager: this.pager,
      topics: this.topics,
      settings: {
        "dots": true,
        "slidesToShow": 3,
        "infinite": false,
        "touchThreshold": 5,
        "centerMode": true
      }
    };
  },
  mounted() {
    this.getPageData('?term=all');
  },
  methods: {
    getPageData(params = '') {
      axios
        .get(`/json${params}`)
        .then((response) => {
          this.title = response.data.title;
          this.pager = response.data.pager;
          this.videos = response.data.videos;
          this.topics = response.data.topics;
        });
    },
  },
};
</script>
