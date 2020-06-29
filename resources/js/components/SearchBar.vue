<template>
  <div
    :class="['search-bar', ...classes]"
  >
    <div
      class="search-bar__action"
    >
      <div class="form__input-wrapper form__input-wrapper--search-bar">
        <input
          :id="searchId"
          ref="searchInput"
          v-model="clonedTerm"
          class="form__input form__input--search form__input--search-bar"
          type="text"
          :name="searchId"
          aria-label="Search"
          placeholder="Search"
          @keypress.enter="search"
        >
        <div class="form__submit-wrapper">
          <button
            :class="['form__submit', 'button', 'button--icon']"
            @click.stop="search"
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
            @click.native="close"
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
              @click.native="close"
            >
              <span class="search-facet__item-text">{{ item.term }}</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { store, mutations } from '../store';

export default {
  name: 'SearchBar',
  components: {},
  props: {
    classes: {
      type: Array,
      default: () => [],
    },
    focus: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      cannedTerms: null,
      clonedTerm: '',
    };
  },
  computed: {
    searchId() {
      return `search-${Math.random().toString(12).substring(4, 8)}`;
    },
    tagClasses() {
      return ['search-facet__item-link', 'link--text', 'link--tag'];
    },
    searchTerm: {
      get() {
        return store.searchTerm;
      },
      set(value) {
        this.setSearchTerm(value);
      },
    },
  },
  mounted() {
    this.getCannedTerms();
    if (this.focus) {
      this.$nextTick(() => {
        if (window.innerWidth > 960) {
          this.$refs.searchInput.focus();
        }
      });
    }
  },
  methods: {
    close() {
      this.$emit('close');
    },
    setSearchTerm: mutations.setSearchTerm,
    search() {
      this.setSearchTerm(this.clonedTerm);
      this.clonedTerm = '';
      this.$router.push({ name: 'search', query: { term: this.searchTerm } }).catch((err) => {
        // @todo Log these rather than swallow?
      });
      this.close();
    },
    getCannedTerms() {
      axios
        .get('/suggestions')
        .then((response) => {
          this.cannedTerms = response.data;
        }).catch((err) => {
          // @todo Log these rather than swallow?
        });
    },
  },
};
</script>
