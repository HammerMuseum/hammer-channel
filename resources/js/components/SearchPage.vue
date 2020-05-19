<template>
  <div class="container">
    <div class="page-wrapper page--search">
      <SearchPageHeader :extra-classes="['heading', 'heading--primary', 'heading--search']">
        <template #summary>
          <AnimatedNumber
            v-show="total"
            :value="total"
            :duration="400"
            :round="1"
          /><span v-show="total"> results</span>
          <div
            v-if="searchSummary"
            class="search-page__summary"
          >
            <span class="search-page__summary__span">for&nbsp;</span>
            <a
              v-if="searchSummary"
              class="search-page__summary__link link link--text link--text-and-button"
              title="Clear term and reset search"
              @click="resetSearch"
            >
              <strong>{{ searchSummary }}</strong>
              <button
                class="button button--icon search-facet__item-remove"
                aria-label="Clear term and reset search"
              >
                <svg class="icon icon--close-pink">
                  <use xlink:href="/images/sprite.svg#sprite-close-pink" />
                </svg>
              </button>
            </a>
          </div>
        </template>
        <template #extras>
          <button
            class="button button--search-toggle button--small-devices"
            @click="toggleSearchFilters"
          >
            {{ !showFilters ? 'Search and filter' : 'Show results' }}
          </button>
          <VToggle
            transition="slide-down"
            :classes="{
              root: 'search-page__sorting',
              label: 'search-page__sorting-control button',
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
                    class="link link--text"
                    :to="{
                      name: 'search',
                      query: {
                        ...$route.query,
                        ...{ sort: 'date_recorded', order: 'asc' }
                      }
                    }"
                  >
                    Date (newest-oldest)
                  </RouterLink>
                </li>
                <li>
                  <RouterLink
                    class="link link--text"
                    :to="{
                      name: 'search',
                      query: {
                        ...$route.query,
                        ...{ sort: 'date_recorded', order: 'desc' }
                      }
                    }"
                  >
                    Date (oldest-newest)
                  </RouterLink>
                </li>
              </ul>
            </template>
          </VToggle>
        </template>
        <CurrentSearch />
      </SearchPageHeader>

      <div class="search-page__content">
        <vue-progress-bar class="search-page__progress-bar" />
        <aside class="search-page__sidebar">
          <transition
            name="slide-in"
            @after-enter="afterEnter"
            @after-leave="afterLeave"
          >
            <div
              v-show="showFilters && hasFacets"
              :class="['search__filters-overlay', {'search__filters-overlay--active': showFilters}]"
              @click.stop="away($event)"
              @keyup.escape="away($event)"
            >
              <div
                ref="searchFilters"
                :class="['search__filters']"
              >
                <button
                  class="button button--search-toggle button--small-devices"
                  @click="showFilters = false"
                >
                  {{ 'Show results' }}
                </button>
                <div class="search-page__form">
                  <div class="form__input-wrapper">
                    <input
                      ref="search"
                      v-model="clonedTerm"
                      label="Search the video archive"
                      name="term"
                      class="form__input form__input--light form__input--search"
                      placeholder="Search"
                      @keyup.enter="submitSearch($event)"
                    >
                    <div class="form__submit-wrapper">
                      <button
                        :class="['form__submit', 'form__submit--small', 'button', 'button--icon']"
                        @click="submitSearch($event)"
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

                <h2 class="heading heading--secondary">
                  Filter
                </h2>
                <div
                  v-if="facets"
                  class="search-page__facet-controls"
                >
                  <button
                    data-facet-id="topics"
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': openFacetName === 'topics'}
                    ]"
                    :disabled="!topicsAndTags.length"
                    @click="toggleFacetOverlay('topics')"
                  >
                    <span>Topics & Tags</span>
                  </button>
                  <button
                    data-facet-id="people"
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': openFacetName === 'people'}]"
                    :disabled="!facets.speakers.items.length"
                    @click="toggleFacetOverlay('people')"
                  >
                    <span>People</span>
                  </button>
                  <button
                    data-facet-id="playlists"
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': openFacetName === 'playlists'}]"
                    :disabled="!facets.in_playlists.items.length"
                    @click="toggleFacetOverlay('playlists')"
                  >
                    <span>Playlists</span>
                  </button>
                  <button
                    data-facet-id="date"
                    :class="['button',
                             'search-facet__label',
                             {'search-facet__label--active': openFacetName === 'date'}]"
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
            @enter="setElementHeight('.overlay__inner', '.search-page__content')"
            @before-enter="toggleFacetOverlayActive()"
            @before-leave="toggleFacetOverlayActive()"
          >
            <Overlay
              v-if="openFacetName === 'topics'"
              id="topics"
              key="topics"
              @close-panel="closeFacetOverlay"
            >
              <SearchableFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet-list="topicsAndTags"
                :panel-name="'topics'"
                @change="toggleFacetOverlay"
              />
            </Overlay>

            <Overlay
              v-if="openFacetName === 'people'"
              id="people"
              key="people"
              @close-panel="closeFacetOverlay"
            >
              <SearchableFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet-list="[facets.speakers]"
                :panel-name="'people'"
                @change="toggleFacetOverlay"
              />
            </Overlay>

            <Overlay
              v-if="openFacetName === 'playlists'"
              id="playlists"
              key="playlists"
              @close-panel="closeFacetOverlay"
            >
              <SearchFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.in_playlists"
                @change="toggleFacetOverlay"
              />
            </Overlay>

            <Overlay
              v-if="openFacetName === 'date'"
              id="date"
              key="date"
              @close-panel="closeFacetOverlay"
            >
              <SearchFacet
                v-if="facets"
                :active-facets="activeFacets"
                :facet="facets.date_recorded"
                @change="toggleFacetOverlay"
              />
            </Overlay>
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
                  :to="{name: 'video', params: {id: item.asset_id, slug: item.title_slug }}"
                  tabindex="0"
                >
                  <div class="ui-card__thumbnail">
                    <span class="ui-card__duration">{{ item.duration }}</span>
                    <img
                      :src="`/images/${item.thumbnailId}/medium`"
                      class="ui-card__thumbnail-image"
                    >
                  </div>
                  <article>
                    <h2 class="ui-card__title">
                      <span v-html="highlight(item)" />
                    </h2>
                    <div class="ui-card__date">
                      {{ new Date(item.date_recorded) | dateFormat('MMM DD, YYYY') }}
                    </div>
                    <SearchSnippets
                      :snippets="item.snippets"
                    />
                  </article>
                </RouterLink>
              </UiCard>
            </UiGrid>

            <template v-if="paginationLinks">
              <Pagination
                :pagination-links="paginationLinks"
              />
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { debounce, _escape } from 'lodash';
import AnimatedNumber from 'animated-number-vue';
import { VToggle } from 'vuetensils';
import UiCard from './UiCard.vue';
import UiGrid from './UiGrid.vue';
import SearchSnippets from './SearchSnippets.vue';
import getRouteData from '../mixins/getRouteData';
import stringifyQuery from '../mixins/stringifyQuery';
import Pagination from './Pagination.vue';
import CurrentSearch from './CurrentSearch.vue';
import SearchFacet from './SearchFacet.vue';
import SearchableFacet from './SearchableFacet.vue';
import SearchPageHeader from './SearchPageHeader.vue';
import Overlay from './Overlay.vue';
import { store, mutations } from '../store';

export default {
  name: 'Search',
  components: {
    AnimatedNumber,
    CurrentSearch,
    Pagination,
    SearchableFacet,
    SearchSnippets,
    SearchFacet,
    SearchPageHeader,
    Overlay,
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
      openFacetName: null,
      clearedSortQuery: null,
      clearPageQuery: null,
      currentQuery: null,
      facets: null,
      loading: false,
      noResults: false,
      pager: null,
      searchSummary: '',
      showFilters: false,
      clonedTerm: '',
      title: null,
      totals: null,
      videos: null,
      debouncedResize: null,
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
      return this.total;
    },
    topicsAndTags() {
      return [this.facets.topics, this.facets.tags];
    },
    total() {
      return this.totals ? this.totals.total : 0;
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
    searchTerm() {
      return store.searchTerm;
    },
  },
  watch: {
    $route: {
      immediate: true,
      handler(to) {
        if (to.query && to.query.term) {
          this.setSearchTerm(to.query.term);
        }
        this.getPageData(stringifyQuery(to.query));
      },
    },
    videos(to) {
      this.noResults = to && to.length === 0;
      this.searchSummary = this.searchTerm;
    },
  },
  mounted() {
    this.width = window.innerWidth;
    this.showFilters = this.width >= 960;
    this.debouncedResize = debounce(this.handleResize, 200);
    window.addEventListener('resize', this.debouncedResize, false);
  },
  beforeDestroy() {
    window.addEventListener('resize', this.debouncedResize, false);
  },
  methods: {
    setSearchTerm: mutations.setSearchTerm,
    toggleFacetOverlayActive: mutations.toggleFacetOverlayActive,
    getPageData(params = '') {
      this.$Progress.start();
      this.videos = null;
      axios
        .get(`/api/search${params}`)
        .then((response) => {
          this.setVars(response);
          this.$Progress.finish();
        }).catch((err) => {
          console.error(err);
          this.$Progress.fail();
        });
    },
    handleResize() {
      if (this.width !== window.innerWidth) {
        this.showFilters = window.innerWidth >= 960;
      }
    },
    highlight(item) {
      if (this.searchTerm) {
        const div = document.createElement('div');
        div.innerText = this.searchTerm;
        const escapedTerm = div.innerHTML;
        const regex = new RegExp(`(${escapedTerm.replace(/([-/\\^$*+?.()|[\]{}])/i, '\\$&')})`, 'i');
        return item.title.replace(regex, '<em>$1</em>');
      }
      return item.title;
    },
    afterEnter() {
      this.filtersAreOpen = true;
    },
    afterLeave() {
      this.filtersAreOpen = false;
    },
    away(event) {
      // const facetList = e.target.parentNode.classList.contains('icon--close');
      // if (!facetList && window.innerWidth < 960 && this.filtersAreOpen) {
      //   this.showFilters = false;
      // }
      // return false;
      if (event.target !== event.currentTarget) {
        return;
      }
      this.showFilters = false;
    },
    resetSearch() {
      this.$router.push({ name: 'search' }).catch(() => {});
      this.clonedTerm = '';
      this.setSearchTerm(this.clonedTerm);
    },
    submitSearch() {
      let searchParams = {};
      if (this.clonedTerm) {
        searchParams = { term: this.clonedTerm };
      }
      this.$router.push({ name: 'search', query: searchParams }).catch(() => {});
      this.$refs.search.blur();
      this.setSearchTerm(this.clonedTerm);
      this.clonedTerm = '';
    },
    setElementHeight(selector, parent) {
      const el = document.querySelector(selector);
      if (window.innerWidth > 960) {
        const parentOffset = document.querySelector(parent).offsetTop;
        const h = window.innerHeight - parentOffset;
        el.style.height = `${h}px`;
      } else {
        el.style.height = '';
      }
    },
    // Set component data when a response is fetched.
    setVars(response) {
      const data = response.data;
      this.totals = data.totals;
      this.title = data.title;
      this.pager = data.pager;
      this.videos = data.videos;
      this.facets = data.facets;
      this.clearPageQuery = data.clearedPageQuery;
      this.clearedSortQuery = data.clearedSortQuery;
      this.currentQuery = data.currentQuery;
    },
    toggleFacetOverlay(name) {
      this.openFacetName = this.openFacetName === name ? null : name;
    },
    toggleSearchFilters() {
      this.showFilters = !this.showFilters;
      if (this.showFilters) {
        document.querySelector(`button[data-facet-id="topics"]`).focus();
      }
    },
    closeFacetOverlay() {
      this.openFacetName = null;
      this.toggleFacetOverlayActive();
    },
  },
};
</script>
