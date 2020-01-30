<template>
    <div class="listing">
        <h1 class="title">{{ title }}</h1>
        <result-grid :videos="videos"></result-grid>
        <div class="pager">
            <ul>
                <li v-for="(item, index) in pager">
                    <a :href="`${item}`">{{ index }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import ResultGrid from "./ResultGridComponent.vue";
    import mixin  from '../mixin';
    export default {
        name: 'Home',
        mixins: [ mixin ],
        data() {
            return {
                title: this.title,
                videos: this.videos,
                pager: this.pager
            }
        },
        mounted() {
            axios
                .get(`http://video.rufio.office.cogapp.com/json`)
                .then((response) => {
                    this.title = response.data.state.original.title;
                    this.pager = response.data.state.original.pager;
                    this.videos = response.data.state.original.videos;
                });
        }
    }
</script>
