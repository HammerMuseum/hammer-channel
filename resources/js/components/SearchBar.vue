<template>
  <focus-trap
    :active="searchActive"
    :escape-deactivates="false"
  >
    <transition name="slide-down">
      <div
        v-if="searchActive"
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
                class="search-bar__input"
                type="text"
                name="search"
                aria-label="Search"
                placeholder="Search"
                @keyup.enter="search"
              >
            </div>

            <div class="search-bar__options">
              <div class="search-bar__option">
                <span>or</span>
                <router-link
                  class="link link--text"
                  :to="{ name: 'search' }"
                  @click.native="toggleSearchActive"
                >
                  show me everything
                </router-link>
              </div>
              <div class="search-bar__option">
                <span class="search-bar__option-label">or try</span>
                <div class="search-bar__option-content">
                  <router-link
                    v-for="item in cannedTerms"
                    :key="item.id"
                    class="link link--text"
                    :to="{ name: 'search', query: item.query }"
                    @click.native="toggleSearchActive"
                  >
                    {{ item.term }}
                  </router-link>
                </div>
              </div>
            </div>
            <button
              class="button--close-search"
              @click="toggleSearchActive"
            >
              close
            </button>
          </div>
        </div>
      </div>
    </transition>
  </focus-trap>
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

<style>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: transform 1.2s cubic-bezier(0.19, 1, 0.22, 1);
}

.slide-down-enter,
.slide-down-leave-to {
  transform: translate3d(0, calc(-100% - 72px), 0);
}

.slide-down-leave,
.slide-down-enter-to {
  transform: translate3d(0, 0, 0);
}
</style>
