<template>
  <div
    v-hammer:swipe.up="close"
    :class="['search-bar', ...classes]"
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
          :name="inputId"
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
            :class="['link--text', 'link--text-secondary', 'link--tag']"
            :to="{ name: 'search', query: {} }"
            @click.native="close"
          >
            <span class="link--tag__text">show me everything</span>
          </RouterLink>
        </div>
        <div class="search-bar__option search-bar__option--right">
          <span class="search-bar__option-label">or try</span>
          <TagGroup
            :items="tagItems"
            @tag-selected="close"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import TagGroup from './TagGroup.vue';
import { store, mutations } from '../store';

export default {
  name: 'SearchBar',
  components: {
    TagGroup,
  },
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
      tagItems: null,
      clonedTerm: '',
    };
  },
  computed: {
    inputId() {
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
    this.getTagItems();
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
    getTagItems() {
      axios
        .get('/suggestions')
        .then((response) => {
          this.tagItems = response.data;
        }).catch((err) => {
          // @todo Log these rather than swallow?
        });
    },
  },
};
</script>
