<template>
  <div class="listing">
    <router-link :to="{name: 'search'}">
      Search
    </router-link>
    <h1 class="title">
      {{ title }}
    </h1>
    <result-grid :videos="videos" />
    <div class="pager">
      <ul>
        <li
          v-for="(item, index) in pager"
          v-if="item !== ''"
        >
          <router-link
            :to="{name: 'app'}"
            @click.native="getPageData(item)"
          >
            {{ index }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import ResultGrid from './ResultGridComponent.vue';
import mixin from '../mixin';

export default {
  name: 'Home',
  mixins: [mixin],
  data() {
    return {
      title: this.title,
      videos: this.videos,
      pager: this.pager,
    };
  },
  mounted() {
    this.getPageData();
  },
  methods: {
    getPageData(params = '') {
      axios
        .get(`/json${params}`)
        .then((response) => {
          this.title = response.data.title;
          this.pager = response.data.pager;
          this.videos = response.data.videos;
        });
    },
  },
};
</script>
