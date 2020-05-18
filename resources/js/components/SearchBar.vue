<template>
  <FocusTrap
    :active="searchOverlayActive"
    :escape-deactivates="false"
  >
    <transition name="open-overlay-down">
      <div
        v-if="searchOverlayActive"
        :class="{ 'search-bar-container': true, 'search-bar-container--visible': searchOverlayActive }"
        @keyup.escape="toggleSearchOverlayActive"
        @click.stop="away"
      >
        <div
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
                    @click.native="toggleSearchOverlayActive"
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
                      @click.native="toggleSearchOverlayActive"
                    >
                      {{ item.term }}
                    </RouterLink>
                  </div>
                </div>
              </div>
              <button
                class="button--close-search"
                @click="toggleSearchOverlayActive"
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
    searchOverlayActive() {
      return store.searchOverlayActive;
    },
  },
  watch: {
    searchOverlayActive() {
      this.$nextTick(() => {
        if (this.searchOverlayActive) {
          this.$refs.searchInput.focus();
        }
      });
    },
  },
  mounted() {
    this.getCannedTerms();
  },
  methods: {
    toggleSearchOverlayActive: mutations.toggleSearchOverlayActive,
    setSearchTerm: mutations.setSearchTerm,
    away(event) {
      if (event.target !== event.currentTarget) {
        return;
      }
      this.toggleSearchOverlayActive();
    },
    search() {
      this.setSearchTerm(this.clonedTerm);
      this.toggleSearchOverlayActive();
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
