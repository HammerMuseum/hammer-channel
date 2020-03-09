<template>
  <div class="listing">
    <ul class="nav-list">
      <li class="nav-item">
        <router-link :to="{name: 'app'}">
          Home
        </router-link>
      </li>
      <li class="nav-item">
        <router-link :to="{name: 'search'}">
          Search
        </router-link>
      </li>
    </ul>
    <div class="search">
      <label
        for="search-main"
        class="search__label"
      />
      <div class="search__item-wrapper">
        <input
          id="search-main"
          v-model="term"
          type="search"
          name="term"
          title="Search"
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
          v-show="showFilters && hasFacets"
          class="facets"
        >
          <h2>Filter by</h2>
          <div
            v-for="facet in facets"
            :key="facet.id"
          >
            <search-facet
              :active-facets="activeFacets"
              :facet="facet"
            />
          </div>
        </div>
      </div>
      <div class="results">
        <div class="facets__sort">
          <span class="facet__label">Order by</span>
          <router-link
            :to="{ name: 'search', query: { ...$route.query, ...{ sort: 'date_recorded', order: 'asc' } } }"
          >
            Date (ASC)
          </router-link>
          <router-link
            :to="{ name: 'search', query: { ...$route.query, ...{ sort: 'date_recorded', order: 'desc' } } }"
          >
            Date (DESC)
          </router-link>
        </div>
        <result-grid :videos="videos" />
        <div class="pager">
          <ul>
            <li
              v-for="(item, label) in filteredPager"
              :key="item"
            >
              <router-link
                v-if="item"
                :to="{ name: 'search', query: { ...$route.query, ...{ start: item.split('=').pop() } } }"
              >
                {{ label | capitalize }}
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
import getRouteData from '../mixins/getRouteData';
import stringifyQuery from '../mixins/stringifyQuery';
import SearchFacet from './SearchFacet.vue';

export default {
  name: 'Search',
  components: {
    ResultGrid,
    SearchFacet,
  },
  filters: {
    capitalize(value) {
      if (!value) return '';
      return value.toString().charAt(0).toUpperCase() + value.slice(1);
    },
  },
  mixins: [getRouteData],
  props: {
    facetQuery: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      showFilters: true,
      title: null,
      videos: null,
      pager: null,
      term: null,
      facets: null,
      clearPageQuery: null,
      clearedSortQuery: null,
      currentQuery: null,
    };
  },
  computed: {
    hasFacets() {
      return !!this.facets;
    },
    activeFacets() {
      const active = [];
      const query = this.$route.query;
      const keys = Object.keys(query);
      for (let i = 0; i < keys.length; i += 1) {
        const key = keys[i];
        active.push(query[key]);
      }
      return [].concat(...active);
    },
    filteredPager() {
      if (this.pager) {
        // Filter out empty properties in the pager.
        return Object.entries(this.pager).reduce((a, [k, v]) => (v ? { ...a, [k]: v } : a), {});
      }
      return false;
    },
  },
  watch: {
    $route: {
      handler(to, from) {
        const oldQuery = from.query.term;
        const newQuery = to.query.term;
        if (oldQuery !== newQuery) {
          this.term = newQuery;
        }
        this.getPageData(stringifyQuery(to.query));
      },
    },
  },
  mounted() {
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
    getPageData(params = '') {
      axios
        .get(`/api/search${params}`)
        .then((response) => {
          this.setVars(response);
        });
    },
    // Perform a search
    search() {
      let searchParams = {};
      if (this.term) {
        searchParams = { term: this.term };
      }
      this.$router.push({ name: 'search', query: { ...this.$route.query, ...searchParams } });
    },
    // Set component data when a response is fetched.
    setVars(response) {
      const data = response.data;
      this.title = data.title;
      this.pager = data.pager;
      this.videos = data.videos;
      this.facets = data.facets;
      this.term = data.term;
      this.clearPageQuery = data.clearedPageQuery;
      this.clearedSortQuery = data.clearedSortQuery;
      this.currentQuery = data.currentQuery;
    },
  },
};
</script>
