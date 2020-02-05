<template>
  <div class="listing">
    <div class="search">
      <label
        for="search-main"
        class="search__label"
      />
      <div class="search__item-wrapper">
        <input
          id="search-main"
          type="search"
          value=""
          name="term"
          title="Search"
          aria-controls=""
          placeholder="Search"
          class="search__item search__input"
          @keyup.enter="search()"
        >
        <div class="search__item search__submit-wrapper">
          <button
            class="search__submit"
            @click="search()"
          >
            Search
          </button>
        </div>
      </div>
    </div>
    <div class="search__area">
      <button
        class="filters__toggle"
        @click="showFilters = !showFilters"
      >
        {{ showFilters ? 'Hide filters' : 'Show filters' }}
      </button>
      <div class="filters">
        <div
          v-show="showFilters"
          class="facets"
        >
          <h2>Filter by</h2>
          <ul
            v-for="(facet, label) in facets"
            :key="label"
            class="facet"
          >
            <p class="facet__label">
              {{ label }}
            </p>
            <li
              v-for="option in facet"
              :key="option.key"
              class="facet__item"
            >
              <router-link
                v-if="label == 'Year Recorded'"
                :to="{name: 'search'}"
                @click.native="filter(`facets=[0]date_recorded:${getYear(option.key_as_string)}`)"
              >
                {{ getYear(option.key_as_string) }}
              </router-link>
              <router-link
                v-if="label == 'Program Series'"
                :to="{name: 'search'}"
                @click.native="filter(`facets=[1]program_series:${option.key}`)"
              >
                {{ option.key }}
              </router-link>
              <router-link
                v-if="label == 'Speakers'"
                :to="{name: 'search'}"
                @click.native="filter(`facets=[2]speakers:${option.key}`)"
              >
                {{ option.key }}
              </router-link>
            </li>
            <router-link
              :to="{name: 'search'}"
              class="facet__clear"
              @click.native="search()"
            >
              Clear filter
            </router-link>
          </ul>
        </div>
      </div>
      <div class="results-">
        <div class="facets__sort">
          <span class="facet__label">Order by</span>
          <router-link
            :to="{name: 'search'}"
            @click.native="sort(
              `${clearedSortQuery}&sort=date_recorded&order=asc`
            )"
          >
            Date (ASC)
          </router-link>
          <router-link
            :to="{name: 'search'}"
            @click.native="sort(
              `${clearedSortQuery}&sort=date_recorded&order=desc`
            )"
          >
            Date (DESC)
          </router-link>
        </div>
        <result-grid :videos="videos" />
        <div class="pager">
          <ul>
            <li
              v-for="(item, index) in filteredPager"
              :key="item"
            >
              <router-link
                v-if="item"
                :to="{name: 'search'}"
                @click.native="getPageData(clearPageQuery + item)"
              >
                {{ index | capitalize }}
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import ResultGrid from './ResultGridComponent.vue';
import mixin from '../mixin';

export default {
  name: 'Search',
  components: {
    ResultGrid,
  },
  filters: {
    capitalize(value) {
      if (!value) return '';
      return value.toString().charAt(0).toUpperCase() + value.slice(1);
    },
  },
  data() {
    return {
      showFilters: true,
      title: this.title,
      videos: this.videos,
      pager: this.pager,
      term: this.term,
      facets: this.facets,
      clearPageQuery: this.clearPageQuery,
      clearedSortQuery: this.clearedSortQuery,
      currentQuery: this.currentQuery,
    };
  },
  computed: {
    filteredPager() {
      if (this.pager) {
        // Filter out empty properties in the pager.
        return Object.entries(this.pager).reduce((a, [k, v]) => (v ? { ...a, [k]: v } : a), {});
      }
      return false;
    },
  },
  mounted() {
    // @todo make this use the actual search term
    this.getPageData('');
    this.clearedSortQuery = '?';
    window.addEventListener('resize', this.handleResize);
    this.handleResize();
  },
  methods: {
    handleResize() {
      if (window.innerWidth >= 850) {
        this.showFilters = true;
      } else {
        this.showFilters = false;
      }
    },
    // Initially, AJAX request the search JSON endpoint and set results on component
    getPageData(params = '') {
      axios
        .get(`/searchJson${params}`)
        .then((response) => {
          this.setVars(response);
        });
    },
    // Perform a search
    search() {
      let searchParams = '';
      const searchTerm = document.querySelector('[name=term]');
      searchParams += `?term=${searchTerm.value}`;
      axios
        .get(`/searchJson${searchParams}`)
        .then((response) => {
          this.setVars(response);
        });
    },
    // Expected querystring format: field_name:value
    filter(queryString) {
      let filterParams = '';
      if (this.term != null) {
        filterParams += `term=${this.term}&`;
      }
      filterParams += queryString;
      filterParams += this.currentQuery;
      axios
        .get(`/searchJson?${filterParams}`)
        .then((response) => {
          this.setVars(response);
        });
    },
    // Sort the results
    sort(queryString) {
      axios
        .get(`/searchJson${queryString}`)
        .then((response) => {
          this.setVars(response);
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
      const date = new Date(dateString);
      return date.getFullYear();
    },
  },
};
</script>
