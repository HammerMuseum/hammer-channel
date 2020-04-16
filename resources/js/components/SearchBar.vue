<template>
  <FocusTrap
    :active="searchActive"
    :escape-deactivates="false"
  >
    <transition name="open-overlay-down">
      <div
        v-show="searchActive"
        :class="{ 'search-bar-container': true, 'search-bar-container--visible': searchActive }"
        @keyup.escape="toggleSearchActive"
      >
        <div
          class="search-bar__body"
        >
          <div
            class="search-bar"
          >
            <div
              ref="searchBar"
              class="search-bar__action"
              tabindex="0"
            >
              <input
                ref="searchInput"
                v-model="searchTerm"
                class="search__input search__input--search-bar"
                type="text"
                name="search"
                aria-label="Search"
                placeholder="Search"
                @keyup.enter="search"
              >
            </div>

            <div class="search-bar__options">
              <div class="search-bar__option search-bar__option--left">
                <span>or</span>
                <RouterLink
                  class="link link--text"
                  :to="{ name: 'search' }"
                  @click.native="toggleSearchActive"
                >
                  show me everything
                </RouterLink>
              </div>
              <div class="search-bar__option search-bar__option--right">
                <span class="search-bar__option-label">or try</span>
                <div class="search-bar__option-content">
                  <RouterLink
                    v-for="item in cannedTerms"
                    :key="item.id"
                    class="link link--text"
                    :to="{ name: 'search', query: item.query }"
                    @click.native="toggleSearchActive"
                  >
                    {{ item.term }}
                  </RouterLink>
                </div>
              </div>
            </div>
            <button
              class="button--close-search"
              @click="toggleSearchActive"
            >
              <span class="visually-hidden">Close search</span>
              <svg
                title="Close search"
                class="icon icon--hover-rotate"
              >
                <use xlink:href="/images/sprite.svg#sprite-close" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </transition>
  </FocusTrap>
</template>

<script>
import { FocusTrap } from 'focus-trap-vue';
import axios from 'axios';
import { store, mutations } from '../store';

export default {
  components: {
    FocusTrap,
  },
  data() {
    return {
      cannedTerms: [],
    };
  },
  computed: {
    searchTerm: {
      get() {
        return store.searchTerm;
      },
      set(value) {
        this.setSearchTerm(value);
      },
    },
    searchActive() {
      return store.searchActive;
    },
  },
  watch: {
    searchActive() {
      this.$nextTick(() => {
        if (this.searchActive) {
          this.$refs.searchBar.focus();
        }
      });
    },
    $route: {
      immediate: true,
      handler(to, from) {
        this.getCannedTerms();
      },
    },
  },
  methods: {
    toggleSearchActive: mutations.toggleSearchActive,
    setSearchTerm: mutations.setSearchTerm,
    search() {
      this.$router.push({ name: 'search', query: { term: this.searchTerm } }).catch();
      this.toggleSearchActive();
    },
    getCannedTerms() {
      axios
        .get('/suggestions')
        .then((response) => {
          this.cannedTerms = response.data;
        });
    },
  },
};
</script>
