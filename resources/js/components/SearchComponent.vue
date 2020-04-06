<template>
  <div class="container">
    <div class="search">
      <div class="search__sidebar">
        <div class="search__form">
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
        <div class="search__filters">
          <div
            v-show="showFilters && hasFacets"
            class="facets"
          >
            <h2>Filter by</h2>
            <div>
              <p
                class="search-facet__label"
                @click="toggleFacetPanel('topics')"
              >
                Topics & tags
              </p>
              <div
                v-show="activeFacet === 'topics'"
                class="searchable-facet__container"
                :class="{active: activeFacet === 'topics'}"
              >
                <span
                  class="close-button"
                  @click="toggleFacetPanel(this, 'topics')"
                >Close</span>
                <searchable-facet
                  v-if="facets"
                  :active-facets="activeFacets"
                  :facet-list="topicsAndTags"
                  :panel-name="'topics'"
                />
              </div>
            </div>
            <p
              class="search-facet__label"
              @click="toggleFacetPanel('speakers')"
            >
              People
            </p>
            <div
              v-show="activeFacet === 'speakers'"
              class="searchable-facet__container"
              :class="{active: activeFacet === 'speakers'}"
            >
              <span
                class="close-button"
                @click="toggleFacetPanel(this, 'speakers')"
              >Close</span>
              <searchable-facet
                v-if="facets"
                :active-facets="activeFacets"
                :facet-list="[facets.speakers]"
                :panel-name="'speakers'"
              />
            </div>

            <div>
              <p class="facet__label">
                Playlist
              </p>
              <search-facet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.in_playlists"
              />
            </div>

            <div>
              <p class="facet__label">
                Year Recorded
              </p>
              <search-facet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.date_recorded"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="search__main">
        <div class="search__header">
          <h1>Search results</h1>
          <button
            class="filters__toggle"
            @click="showFilters = !showFilters"
          >
            {{ showFilters ? 'Hide filters' : 'Show filters' }}
          </button>

          <div class="search__sorting">
            <label>Order by</label>
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
        </div>

        <div
          v-if="noResults"
          class="no-results"
        >
          <span class="label">
            There are no results that match your criteria.</span>
        </div>
        <div
          v-else
          class="search__results"
        >
          <div class="totals">
            Showing {{ currentResultStart }} to {{ currentResultEnd }} of {{ total }}
          </div>

          <ui-grid>
            <ui-card
              v-for="item in videos"
              :key="item.title_slug"
            >
              <router-link
                :to="{name: 'video', params: {id: item.slug }}"
              >
                <div class="ui-card__thumbnail">
                  <span class="ui-card__duration">{{ item.duration }}</span>
                  <img
                    :src="item.thumbnail_url"
                    class="ui-card__thumbnail-image"
                  >
                </div>
                <article>
                  <h2 class="ui-card__title">
                    <span>{{ item.title }}</span>
                  </h2>
                  <!-- <p class="ui-card__description">
                    {{ item.description }}
                  </p> -->
                </article>
              </router-link>

              <!-- <span class="video__duration">{{ item.duration }}</span>
              <router-link
                :to="{name: 'video', params: {id: item.title_slug}}"
              >
                <img
                  class="result-item__image"
                  :src="`${ item.thumbnail_url }`"
                >
                <span class="result-item__title">{{ item.title }}</span>
              </router-link> -->
            </ui-card>
          </ui-grid>

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
  </div>
</template>

<script>
import axios from 'axios';
import UiCard from './UiCard.vue';
import UiGrid from './UiGrid.vue';
import getRouteData from '../mixins/getRouteData';
import stringifyQuery from '../mixins/stringifyQuery';
import SearchFacet from './SearchFacet.vue';
import SearchableFacet from './SearchableFacet.vue';

export default {
  name: 'Search',
  components: {
    UiCard,
    UiGrid,
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
      if (!this.topicsAndTags.length) {
        this.topicsAndTags.push(this.facets.topics);
        this.topicsAndTags.push(this.facets.tags);
      }
    },
    toggleFacetPanel(name) {
      this.activeFacet = this.activeFacet === name ? null : name;
    },
  },
};
</script>
