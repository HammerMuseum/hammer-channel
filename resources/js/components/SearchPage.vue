<template>
  <div class="container">
    <div class="search-page">
      <SearchPageHeader>
        <div class="totals">
          Showing {{ currentResultStart }} to {{ currentResultEnd }} of {{ total }}
        </div>
      </SearchPageHeader>
      <div class="search-page__content">
        <div class="search-page__sidebar">
          <button
            class="button button--ui-toggle"
            @click="showFilters = !showFilters"
          >
            {{ !showFilters ? 'Search and filter' : 'Hide filters' }}
          </button>

          <transition name="slide-in">
            <div
              v-show="showFilters && hasFacets"
              :class="['search__filters-overlay', {'search__filters-overlay--active': showFilters}]"
            >
              <div
                :class="['search__filters']"
              >
                <div class="search-page__form">
                  <label
                    class="visually-hidden search__label"
                    for="search-main"
                  >Search the video archive</label>
                  <div class="search__input-wrapper">
                    <input
                      id="search-main"
                      v-model="term"
                      name="term"
                      title="Search"
                      placeholder="Search"
                      class="search__input"
                      type="text"
                      aria-label="Search"
                      @keyup.enter="search()"
                    >
                    <div class="search__submit-wrapper">
                      <button
                        :class="['search__submit', 'button', 'button--icon']"
                        @click="search()"
                      >
                        <svg
                          title="Search"
                          class="icon"
                        >
                          <use xlink:href="/images/sprite.svg#sprite-search" />
                        </svg>
                        <span class="icon-text visually-hidden">Search</span>
                      </button>
                    </div>
                  </div>
                </div>

                <h2 class="page-heading--secondary">
                  Filter
                </h2>
                <button
                  :class="['button', 'search-facet__label', {'search-facet__label--active': activeFacet === 'topics'}]"
                  @click="changeFacetOverlay('topics')"
                >
                  <span>Topics & Tags</span>
                </button>
                <button
                  :class="['button', 'search-facet__label', {'search-facet__label--active': activeFacet === 'people'}]"
                  @click="changeFacetOverlay('people')"
                >
                  <span>People</span>
                </button>
                <button
                  :class="['button', 'search-facet__label', {'search-facet__label--active': activeFacet === 'playlists'}]"
                  @click="changeFacetOverlay('playlists')"
                >
                  <span>Playlists</span>
                </button>
                <button
                  :class="['button', 'search-facet__label', {'search-facet__label--active': activeFacet === 'date'}]"
                  @click="changeFacetOverlay('date')"
                >
                  <span>Date</span>
                </button>
              </div>
            </div>
          </transition>
        </div>

        <div class="search-page__main">
          <transition
            name="slide-out"
            mode="out-in"
          >
            <SearchPageOverlay
              v-if="activeFacet === 'topics'"
              id="topics"
              key="topics"
              @closePanel="changeFacetOverlay"
            >
              <SearchableFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet-list="topicsAndTags"
                :panel-name="'topics'"
                @change="changeFacetOverlay"
              />
            </SearchPageOverlay>

            <SearchPageOverlay
              v-if="activeFacet === 'people'"
              id="people"
              key="people"
              @closePanel="changeFacetOverlay"
            >
              <SearchableFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet-list="[facets.speakers]"
                :panel-name="'people'"
                @change="changeFacetOverlay"
              />
            </SearchPageOverlay>

            <SearchPageOverlay
              v-if="activeFacet === 'playlists'"
              id="playlists"
              key="playlists"
              @closePanel="changeFacetOverlay"
            >
              <SearchFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.in_playlists"
                @change="changeFacetOverlay"
              />
            </SearchPageOverlay>

            <SearchPageOverlay
              v-if="activeFacet === 'date'"
              id="date"
              key="date"
              @closePanel="changeFacetOverlay"
            >
              <SearchFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.date_recorded"
                @change="changeFacetOverlay"
              />
            </SearchPageOverlay>
          </transition>

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
            <UiGrid>
              <UiCard
                v-for="item in videos"
                :key="item.title_slug"
              >
                <RouterLink
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
                  </article>
                </RouterLink>
              </UiCard>
            </UiGrid>

            <div class="pager">
              <ul>
                <li
                  v-for="(item, label) in filteredPager"
                  :key="item"
                >
                  <RouterLink
                    v-if="item"
                    :to="{ name: 'search', query: { ...$route.query, ...{ page: item.split('=').pop() } } }"
                  >
                    {{ label | capitalize }}
                  </RouterLink>
                </li>
              </ul>
            </div>
          </div>
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
import SearchPageHeader from './SearchPageHeader.vue';
import SearchPageOverlay from './SearchPageOverlay.vue';

export default {
  name: 'Search',
  components: {
    UiCard,
    UiGrid,
    SearchFacet,
    SearchableFacet,
    SearchPageHeader,
    SearchPageOverlay,
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
      overlayOpen: false,
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
    activeFacet(to) {
      document.body.style.overflow = this.activeFacet ? 'hidden' : '';
      this.$nextTick(() => {
        if (to) {
          document.querySelector(`#${to}`).focus({ preventScroll: true });
        }
      });
    },
    showFilters(to) {
      if (to) {
        document.body.classList.add('search-filters-are-open');
      } else {
        document.body.classList.remove('search-filters-are-open');
      }
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
      if (window.innerWidth >= 840) {
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
    changeFacetOverlay(name) {
      this.activeFacet = this.activeFacet === name ? null : name;
    },
  },
};
</script>
