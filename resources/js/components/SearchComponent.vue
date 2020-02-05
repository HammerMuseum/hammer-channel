<template>
    <div class="listing">
        <div class="search">
            <label for="search-main" class="search__label"></label>
            <div class="search__item-wrapper">
                <input type="search" value="" id="search-main" name="term" title="Search" aria-controls="" placeholder="Search" class="search__item search__input" v-on:keyup.enter="search()">
                <div class="search__item search__submit-wrapper">
                    <button @click='search()' class="search__submit">Search</button>
                </div>
            </div>
        </div>
        <div class="filters">
            <div class="facets">
                <h2>Filter by</h2>
                <p v-for="(facet, label) in this.facets">
                    <span class="facets__label">{{ label }}</span>
                    <span v-for="option in facet">
                        <router-link v-if="label == 'Year Recorded'" :to="{name: 'search'}" v-on:click.native="filter(`facets=[0]date_recorded:${getYear(option.key_as_string)}`)">
                            {{ getYear(option.key_as_string) }}
                        </router-link>
                        <router-link v-if="label == 'Program Series'" :to="{name: 'search'}" v-on:click.native="filter(`facets=[1]program_series:${option.key}`)">
                            {{ option.key }}
                        </router-link>
                         <router-link v-if="label == 'Speakers'" :to="{name: 'search'}" v-on:click.native="filter(`facets=[2]speakers:${option.key}`)">
                            {{ option.key }}
                        </router-link>
                    </span>
                    <router-link :to="{name: 'search'}" v-on:click.native="search()">Clear filter</router-link>
                </p>
            </div>
            <div class="facets__sort">
                <span class="facets__label">Order by</span>
                <router-link :to="{name: 'search'}" v-on:click.native="sort(
                `${clearedSortQuery}&sort=date_recorded&order=asc`
                )">Date (ASC)</router-link>
                <router-link :to="{name: 'search'}" v-on:click.native="sort(
                `${clearedSortQuery}&sort=date_recorded&order=desc`
                )">Date (DESC)</router-link>
            </div>
        </div>

        <result-grid :videos="this.videos"></result-grid>
        <div class="pager">
            <ul>
                <li v-for="(item, index) in this.pager" v-if="item !== ''">
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
                clearPageQuery: this.clearPageQuery,
                clearedSortQuery: this.clearedSortQuery,
                currentQuery: this.currentQuery
            }
        },
        methods: {
            // Initially, AJAX request the search JSON endpoint and set results on component
            getPageData(params = '') {
                axios
                    .get(`/searchJson${params}`)
                    .then((response) => {
                        this.setVars(response)
                    });
            },
            // Perform a search
            search() {
                var searchParams = '';
                var searchTerm = document.querySelector("[name=term]");
                searchParams += '?term=' + searchTerm.value;
                axios
                    .get(`/searchJson${searchParams}`)
                    .then((response) => {
                        this.setVars(response)
                    });
            },
            // Expected querystring format: field_name:value
            filter(queryString) {
                var filterParams = '';
                if (this.term != null) {
                  filterParams += 'term=' + this.term + '&';
                }
                filterParams += queryString;
                filterParams += this.currentQuery;
                console.log(filterParams);
                axios
                    .get(`/searchJson?${filterParams}`)
                    .then((response) => {
                        this.setVars(response)
                    });
            },
            // Sort the results
            sort(queryString) {
                var sortParams = '';
                sortParams += queryString;
                axios
                    .get(`/searchJson${sortParams}`)
                    .then((response) => {
                        this.setVars(response)
                    });
            },
            // Use whatever response current in the application to populate the page
            setVars(response) {
              this.title = response.data.title;
              this.pager = response.data.pager;
              this.videos = response.data.videos;
              this.facets = response.data.facets;
              this.term = response.data.term;
              this.clearPageQuery = response.data.clearedPageQuery;
              this.clearedSortQuery = response.data.clearedSortQuery;
              this.currentQuery = response.data.currentQuery;
            },
            // Extract year from date string
            getYear(dateString) {
                var date = new Date(dateString);
                return date.getFullYear();
            }
        },
        mounted() {
            //@todo make this use the actual search term
            this.getPageData('');
            this.clearedSortQuery = '?';
        }
    }
</script>
