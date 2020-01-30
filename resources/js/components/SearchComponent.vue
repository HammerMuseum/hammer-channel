<template>
    <div class="listing">
        search boi
        <result-grid :videos="results"></result-grid>
    </div>
</template>

<script>
    import ResultGrid from "./ResultGridComponent.vue";
    export default {
        name: 'Search',
        props: {
            results: Array
        },
        data() {
            return {
                results: this.results
            }
        },
        mounted() {
            console.log('Search component mounted.')
        }
    }
</script>
