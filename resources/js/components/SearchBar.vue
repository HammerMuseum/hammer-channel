<template>
  <FocusTrap
    :active="searchActive"
    :escape-deactivates="false"
  >
    <transition name="open-overlay-down">
      <div
        v-if="searchActive"
        :class="{ 'search-bar-container': true, 'search-bar-container--visible': searchActive }"
        @keyup.escape="toggleSearchActive"
      >
        <div
          v-click-outside="toggleSearchActive"
          class="search-bar__body"
        >
          <div
            class="search-bar"
          >
            <div
              class="search-bar__action"
            >
              <div class="form__input-wrapper form__input-wrapper--search-bar">
                <input
                  ref="searchInput"
                  v-model="clonedTerm"
                  class="form__input form__input--search form__input--search-bar"
                  type="text"
                  name="search"
                  aria-label="Search"
                  placeholder="Search"
                  @keyup.enter="search"
                >
                <div class="form__submit-wrapper">
                  <button
                    :class="['form__submit', 'button', 'button--icon']"
                    @click="search"
                  >
                    <svg
                      class="icon"
                    >
                      <use xlink:href="/images/sprite.svg#sprite-search" />
                    </svg>
                    <span class="icon-text visually-hidden">Search</span>
                  </button>
                </div>
              </div>

              <div class="search-bar__options">
                <div class="search-bar__option search-bar__option--left">
                  <span>or</span>
                  <RouterLink
                    :class="tagClasses"
                    :to="{ name: 'search', query: {} }"
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
                      :class="tagClasses"
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
      cannedTerms: null,
      clonedTerm: '',
    };
  },
  computed: {
    tagClasses() {
      return ['link', 'link--text', 'link--tag'];
    },
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
          this.$refs.searchInput.focus();
        }
      });
    },
  },
  mounted() {
    this.getCannedTerms();
  },
  methods: {
    toggleSearchActive: mutations.toggleSearchActive,
    setSearchTerm: mutations.setSearchTerm,
    search() {
      this.setSearchTerm(this.clonedTerm);
      this.toggleSearchActive();
      this.clonedTerm = '';
      this.$router.push({ name: 'search', query: { term: this.searchTerm } }).catch((err) => {
        // @todo Log these to Laravel, not the console
        console.error(err);
      });
    },
    getCannedTerms() {
      axios
        .get('/suggestions')
        .then((response) => {
          this.cannedTerms = response.data;
        }).catch((err) => {
          // @todo Log these to Laravel, not the console
          console.error(err);
        });
    },
  },
};
</script>
