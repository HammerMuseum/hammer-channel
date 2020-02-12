<template>
    <div class="video__info video-wrapper__item">
        <div class="video-info__card">
            <span class="close-button" @click="toggleActivePanel($event, 'about')">X</span>
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
      keywords: Array
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
