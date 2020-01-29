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
    export default {
        name: 'Home',
        props: {
            title: String,
            pager: Object,
            message: String,
            videos: Array
        },
        data() {
            return {
                videos: this.videos
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
