<template>
    <div class="video__info video-wrapper__item">
        <div class="video-info__card" scroll>
            <span class="close-button" @click="toggleActivePanel($event, 'about')">X</span>
            <div v-if="programSeries">
                Part of:
                <router-link :to="{name: 'search', params: {params:`?facets=[1]program_series:${programSeries}`}}">
                    {{ programSeries }}
                </router-link>
            </div>
            <div class="date">
                {{ new Date(date) | dateFormat('dddd, DD MMMM, YYYY') }}
            </div>
            <div class="description">
                {{ description }}
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'About',
    props: {
      date: String,
      description: String,
      keywords: Array,
      programSeries: String
    },
    methods: {
      toggleActivePanel(event, panel) {
        this.$parent.toggleActivePanel(event, panel);
      }
    },
    mounted() {
    },
  };
</script>
