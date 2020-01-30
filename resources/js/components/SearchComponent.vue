<template>
    <div class="listing">
        <div class="search">
            <label for="search-main" class="search__label"></label>
            <div class="search__item-wrapper">
                <input type="search" value="" id="search-main" name="term" title="Search" aria-controls="" placeholder="Search" class="search__item search__input" v-on:keyup.enter="search()">
                <div class="search__item search__submit-wrapper">
                    <!--<button type="submit" >Search</button>-->
                    <button @click='search()' class="search__submit">Search</button>
                </div>
            </div>
        </div>
        <result-grid :videos="this.videos"></result-grid>
        <div class="pager">
            <ul>
                <li v-for="(item, index) in this.pager">
                    <router-link v-if="item" :to="{name: 'search'}" v-on:click.native="getPageData(clearPageQuery + item)">
                        {{ index }}
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import ResultGrid from "./ResultGridComponent.vue";
    import mixin  from '../mixin';
    export default {
        name: 'Search',
        props: {},
        data() {
            return {
                title: this.title,
                videos: this.videos,
                pager: this.pager,
                term: this.term,
                facets: this.facets,
                clearPageQuery: this.clearPageQuery
            }
        },
        methods: {
            getPageData(params = '') {
                console.log(params);
                axios
                    .get(`http://video.rufio.office.cogapp.com/searchJson${params}`)
                    .then((response) => {
                        console.log(response.data)
                        this.setVars(response)
                    });
            },
            search() {
                var searchParams = '';
                var searchTerm = document.querySelector('[name=term]').value;
                searchParams += '?term=' + searchTerm;
                axios
                    .get(`http://video.rufio.office.cogapp.com/searchJson${searchParams}`)
                    .then((response) => {
                        console.log(response)
                        this.setVars(response)
                    });
            },
            setVars(response) {
                this.title = response.data.title;
                this.pager = response.data.pager;
                this.videos = response.data.videos;
                this.facets = response.data.facets;
                this.term = response.data.term;
                this.clearPageQuery = response.data.clearedPageQuery;
            }
        },
        mounted() {
            this.getPageData('?term=poetry');
            console.log('Search component mounted.')
        }
    }
</script>
