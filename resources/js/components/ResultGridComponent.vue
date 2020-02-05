<template>
    <div class="result-grid">
        <div class="result-item" v-for="video in videos" >
            <router-link :to="{name: 'video', props: {
      url: video.video_url,
      aid: video.asset_id,
      title: video.title,
      date: video.date_recorded,
      description: video.description,
      tags: video.tags,
    }, params: {id: video.title_slug}}">
                <img class="result-item__image" :src="`${ video.thumbnail_url }`" />
                <span class="result-item__title">{{ video['title'] }}</span>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ResultGrid",
        props: {
            videos: Array
        }
    }
</script>
