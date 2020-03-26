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
    <div
      v-if="noResults"
      class="search__area"
    >
      <div class="no-results">
        <span class="label">
          There are no results that match your criteria.</span>
      </div>
    </div>
    <div
      v-if="!noResults"
      class="search__area"
    >
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
          <div>
            <p class="search-facet__label" @click="toggleFacetPanel('topics')">
              Topics & tags
            </p>
            <div class="searchable-facet__container"
              v-show="activeFacet === 'topics'"
              :class="{active: activeFacet === 'topics'}"
            >
              <span
                class="close-button"
                @click="toggleFacetPanel(this, 'topics')"
              >Close</span>
              <searchable-facet
                v-if="facets != null"
                :active-facets="activeFacets"
                :facetList="topicsAndTags"
                :panelName="'topics'"
              />
            </div>
          </div>
          <div>
            <p class="facet__label">
              People
            </p>
            <search-facet
              v-if="facets != null"
              :active-facets="activeFacets"
              :facet="facets.speakers"
            />
          </div>
          <div>
            <p class="facet__label">
              Playlist
            </p>
            <search-facet
              v-if="facets != null"
              :active-facets="activeFacets"
              :facet="facets.in_playlists"
            />
          </div>
          <div>
            <p class="facet__label">
              Year Recorded
            </p>
            <search-facet
              v-if="facets != null"
              :active-facets="activeFacets"
              :facet="facets.date_recorded"
            />
          </div>
        </div>
      </div>
      <div class="results">
        <div class="facets__sort">
          <span class="facet__label">Order by</span>
          <router-link
            :to="{
              name: 'search',
              query: {
                ...$route.query,
                ...{ sort: 'date_recorded', order: 'asc' }
              }
            }"
          >
            Date (ASC)
          </router-link>
          <router-link
            :to="{
              name: 'search',
              query: {
                ...$route.query,
                ...{ sort: 'date_recorded', order: 'desc' }
              }
            }"
          >
            Date (DESC)
          </router-link>
        </div>
        <div class="totals">
          Showing {{ currentResultStart }} to {{ currentResultEnd }} of {{ total }}
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
                :to="{ name: 'search', query: { ...$route.query, ...{ page: item.split('=').pop() } } }"
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
import SearchableFacet from './SearchableFacet.vue';

export default {
  name: 'Search',
  components: {
    ResultGrid,
    SearchFacet,
    SearchableFacet,
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
      totalsInfo: null,
      total: null,
      currentResultStart: null,
      currentResultEnd: null,
      noResults: false,
      topicsAndTags: [],
      activeFacet: null,
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
      immediate: true,
      handler(to, from) {
        if (from) {
          const oldQuery = from.query.term;
          const newQuery = to.query.term;
          if (oldQuery !== newQuery) {
            this.term = newQuery;
          }
        }
        this.getPageData(stringifyQuery(to.query));
      },
    },
    videos(value) {
      this.noResults = (value.length === 0);
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
    getPageTotals() {
      if (this.totalsInfo.totalPages === this.currentPage || this.totalsInfo.totalPages === 0) {
        this.currentResultEnd = this.total;
      } else {
        this.currentResultEnd = this.currentPage * 12;
      }
      if (this.currentPage === 0) {
        this.currentResultStart = 1;
      } else {
        this.currentResultStart = 12 * (this.currentPage - 1) + 1;
      }
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
      this.totalsInfo = data.totals;
      this.total = data.totals.total;
      this.currentPage = this.totalsInfo.currentPage;
      this.getPageTotals();
      this.combineTopicsTags();
    },
    combineTopicsTags() {
      if (this.topicsAndTags.length <= 0) {
        this.topicsAndTags.push(this.facets.topics);
        this.topicsAndTags.push(this.facets.tags);
      }
    },
    toggleFacetPanel(name) {
      if (this.activeFacet === name) {
        this.activeFacet = null;
      } else {
        this.activeFacet = name;
      }
    },
  },
};
</script>
