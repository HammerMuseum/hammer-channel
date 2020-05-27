<template>
  <div class="container container--fixed container--top header-container">
    <header class="header">
      <div class="header__title">
        <h1 class="visually-hidden">
          Hammer Video
        </h1>
        <RouterLink
          class="link link--with-image"
          :to="{name: 'app'}"
        >
          <svg
            title="Hammer video logo"
            class="icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-hammer-video" />
          </svg>
        </RouterLink>
      </div>
    </header>
    <button
      class="button search-overlay-toggle"
      @click="toggleSearchOverlayActive"
    >
      <svg
        title="Open search"
        class="icon"
      >
        <use xlink:href="/images/sprite.svg#sprite-search" />
      </svg>
      <span class="icon-text visually-hidden">Open search</span>
    </button>
    <SearchBar />
  </div>
</template>

<script>
import SearchBar from './SearchBar.vue';
import { mutations } from '../store';

export default {
  name: 'TheHeader',
  components: {
    SearchBar,
  },
  data() {
    return {
      title: 'Hammer Video',
    };
  },
  methods: {
    toggleSearchOverlayActive: mutations.toggleSearchOverlayActive,
  },
};
</script>
