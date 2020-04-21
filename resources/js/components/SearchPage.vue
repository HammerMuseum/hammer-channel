<template>
  <div class="container">
    <div class="search-page">
      <SearchPageHeader :extra-classes="['page-heading', 'page-heading--search']">
        <template #summary>
          <span>{{ hitInfo }}</span>
          <RouterLink
            v-if="searchSummary"
            :to="{ name: 'search' }"
            class="link link--text link--text-and-button"
            title="Clear term and reset search"
          >
            <span style="border:none;padding-bottom:2px;">for&nbsp;</span><span>{{ searchSummary }}</span>
            <button
              class="button button--icon search-facet__item-remove"
              aria-label="Clear term and reset search"
            >
              <svg class="icon icon--close">
                <use xlink:href="/images/sprite.svg#sprite-close" />
              </svg>
            </button>
          </RouterLink>
        </template>
        <template #extras>
          <button
            class="button button--search-toggle button--small-devices"
            @click="showFilters = !showFilters"
          >
            {{ !showFilters ? 'Search and filter' : 'Hide filters' }}
          </button>
          <VToggle
            transition="slide-down"
            :classes="{
              root: 'search-page__sorting',
              label: 'search-page__sorting-control button--search-toggle',
              content: 'search-page__sorting-content'
            }"
          >
            <template #label>
              <span
                class="search-page__sorting-control-label"
                aria-hidden
              >
                <span>Sort by</span>
                <svg
                  title="Sorting options"
                  class="icon icon--small"
                >
                  <use xlink:href="/images/sprite.svg#sprite-dropdown" />
                </svg>
              </span>
            </template>

            <template #default>
              <ul>
                <li>
                  <RouterLink
                    class="link link--text link--text-secondary"
                    :to="{
                      name: 'search',
                      query: {
                        ...$route.query,
                        ...{ sort: 'date_recorded', order: 'asc' }
                      }
                    }"
                  >
                    Date (ASC)
                  </RouterLink>
                </li>
                <li>
                  <RouterLink
                    class="link link--text link--text-secondary"
                    :to="{
                      name: 'search',
                      query: {
                        ...$route.query,
                        ...{ sort: 'date_recorded', order: 'desc' }
                      }
                    }"
                  >
                    Date (DESC)
                  </RouterLink>
                </li>
              </ul>
            </template>
          </VToggle>
        </template>
      </SearchPageHeader>

      <!-- <div class="search-page__current-search"></div> -->

      <div class="search-page__content">
        <aside class="search-page__sidebar">
          <transition name="slide-in">
            <div
              v-show="showFilters && hasFacets"
              :class="['search__filters-overlay', {'search__filters-overlay--active': showFilters}]"
            >
              <div
                :class="['search__filters']"
              >
                <button
                  v-show="showFilters"
                  class="button button--search-toggle button--small-devices"
                  @click="showFilters = false"
                >
                  {{ 'Hide filters' }}
                </button>
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
                <div
                  v-if="facets"
                  class="search-page__facet-buttons"
                >
                  <button
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': activeFacetList === 'topics'}
                    ]"
                    :disabled="!topicsAndTags.length"
                    @click="toggleFacetOverlay('topics')"
                  >
                    <span>Topics & Tags</span>
                  </button>
                  <button
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': activeFacetList === 'people'}]"
                    :disabled="!facets.speakers.items.length"
                    @click="toggleFacetOverlay('people')"
                  >
                    <span>People</span>
                  </button>
                  <button
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': activeFacetList === 'playlists'}]"
                    :disabled="!facets.in_playlists.items.length"
                    @click="toggleFacetOverlay('playlists')"
                  >
                    <span>Playlists</span>
                  </button>
                  <button
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': activeFacetList === 'date'}]"
                    :disabled="!facets.date_recorded.items.length"
                    @click="toggleFacetOverlay('date')"
                  >
                    <span>Date</span>
                  </button>
                </div>
              </div>
            </div>
          </transition>
        </aside>

        <div class="search-page__main">
          <transition
            name="slide-out"
            mode="out-in"
          >
            <SearchPageOverlay
              v-if="activeFacetList === 'topics'"
              id="topics"
              key="topics"
              @closePanel="closeFacetOverlay"
            >
              <SearchableFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet-list="topicsAndTags"
                :panel-name="'topics'"
                @change="toggleFacetOverlay"
              />
            </SearchPageOverlay>

            <SearchPageOverlay
              v-if="activeFacetList === 'people'"
              id="people"
              key="people"
              @closePanel="closeFacetOverlay"
            >
              <SearchableFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet-list="[facets.speakers]"
                :panel-name="'people'"
                @change="toggleFacetOverlay"
              />
            </SearchPageOverlay>

            <SearchPageOverlay
              v-if="activeFacetList === 'playlists'"
              id="playlists"
              key="playlists"
              @closePanel="closeFacetOverlay"
            >
              <SearchFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.in_playlists"
                @change="toggleFacetOverlay"
              />
            </SearchPageOverlay>

            <SearchPageOverlay
              v-if="activeFacetList === 'date'"
              id="date"
              key="date"
              @closePanel="closeFacetOverlay"
            >
              <SearchFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.date_recorded"
                @change="toggleFacetOverlay"
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
                v-for="item in hits"
                :key="item.asset_id"
              >
                <RouterLink
                  :to="{name: 'video', params: {id: item.title_slug }}"
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

            <Pagination
              :pagination-links="paginationLinks"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import axios from 'axios';
import { VToggle } from 'vuetensils';
import UiCard from './UiCard.vue';
import UiGrid from './UiGrid.vue';
import getRouteData from '../mixins/getRouteData';
import stringifyQuery from '../mixins/stringifyQuery';
import Pagination from './Pagination.vue';
import SearchFacet from './SearchFacet.vue';
import SearchableFacet from './SearchableFacet.vue';
import SearchPageHeader from './SearchPageHeader.vue';
import SearchPageOverlay from './SearchPageOverlay.vue';

export default {
  name: 'Search',
  components: {
    Pagination,
    SearchableFacet,
    SearchFacet,
    SearchPageHeader,
    SearchPageOverlay,
    UiCard,
    UiGrid,
    VToggle,
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
      activeFacetList: null,
      clearedSortQuery: null,
      clearPageQuery: null,
      currentQuery: null,
      facets: null,
      noResults: false,
      overlayOpen: false,
      pager: null,
      searchSummary: '',
      showFilters: true,
      term: null,
      title: null,
      totals: null,
      videos: null,
    };
  },
  computed: {
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
    paginationLinks() {
      if (this.pager) {
        // Filter out empty properties in the pager.
        return Object.entries(this.pager).reduce((a, [k, v]) => (v ? { ...a, [k]: v } : a), {});
      }
      return false;
    },
    hasFacets() {
      return !!this.facets;
    },
    hits() {
      return this.videos;
    },
    hitInfo() {
      return this.total ? `${this.total} results` : '0 results';
    },
    topicsAndTags() {
      return [this.facets.topics, this.facets.tags];
    },
    total() {
      return this.totals ? this.totals.total : null;
    },
    searchTerm() {
      if (this.term) return this.term;
      return this.$route.query.term ? this.$route.query.term : null;
    },
    currentPage() {
      return this.totals ? this.totals.currentPage : null;
    },
    currentResultEnd() {
      if (!this.totals || !this.currentPage) return null;
      if (this.totals.totalPages === this.currentPage || this.totals.totalPages === 0) {
        return this.total;
      }
      return this.currentPage * 12;
    },
    currentResultStart() {
      if (!this.currentPage) return null;
      if (this.currentPage === 0) {
        return 1;
      }
      return 12 * (this.currentPage - 1) + 1;
    },
  },
  watch: {
    $route: {
      immediate: true,
      handler(to, from) {
        const oldQuery = from && from.query && from.query.term ? from.query.term : '';
        const newQuery = to && to.query && to.query.term ? to.query.term : '';
        if (oldQuery !== newQuery) {
          this.term = newQuery;
        }
        this.getPageData(stringifyQuery(to.query));
        const bodyClasses = document.body.classList;
      },
    },
    activeFacetList(to) {
      // document.body.style.overflow = this.activeFacetList ? 'hidden' : '';
      // this.$nextTick(() => {
      //   if (to) {
      //     document.querySelector(`#${to}`).focus({ preventScroll: true });
      //   }
      // });
    },
    showFilters(to) {
      if (to) {
        document.body.classList.add('search-filters-are-open');
      } else {
        document.body.classList.remove('search-filters-are-open');
      }
    },
    videos(to) {
      this.noResults = to.length === 0;
      this.searchSummary = this.term;
    },
  },
  mounted() {
    window.addEventListener('resize', this.handleResize);
    this.handleResize();
  },
  methods: {
    handleResize() {
      this.showFilters = window.innerWidth >= 960;
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
      this.totals = data.totals;
    },
    toggleFacetOverlay(name) {
      this.activeFacetList = this.activeFacetList === name ? null : name;
    },
    closeFacetOverlay() {
      this.activeFacetList = null;
    },
  },
};
</script>
