<template>
    <div class="result-grid">
        <div class="result-item" v-for="video in videos" >
            <a :href="`video/${ video.asset_id }`">
                <img class="result-item__image" :src="`${ video.thumbnail_url }`" />
                <span class="result-item__title">{{ video['title'] }}</span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ResultGrid",
        props: {
            videos: Array
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
