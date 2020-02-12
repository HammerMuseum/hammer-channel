<template>
    <div class="video__info">
        <div class="video-info__card" scroll>
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
    mounted() {
      console.log('Component mounted.');
      console.log(this.date);
    },
  };
</script>
