<template>
  <div class="header-container">
    <header class="header">
      <div class="header__title">
        <h1 class="visually-hidden">
          Hammer Video
        </h1>
        <router-link
          class="link link--with-image"
          :to="{name: 'app'}"
        >
          <svg
            title="Hammer video logo"
            class="icon"
          >
            <use xlink:href="/images/sprite.svg#sprite-hammer-video" />
          </svg>
        </router-link>
      </div>
    </header>
    <button
      class="button search-overlay-toggle"
      @click="toggleSearchActive"
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
import { mutations } from '../store';

export default {
  data() {
    return {
      title: 'Hammer Video',
    };
  },
  methods: {
    toggleSearchActive: mutations.toggleSearchActive,
  },
};
</script>
