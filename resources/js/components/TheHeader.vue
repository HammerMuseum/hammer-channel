<template>
  <div class="header-container">
    <div class="header">
      <div class="header__logo">
        <a
          class="link link--with-image"
          href="https://hammer.ucla.edu"
        >
          <img
            class="link__image"
            src="/assets/images/logo-hammer.png"
            alt="The Hammer Museum Logo"
          >
        </a>
      </div>
      <div class="header__title">
        <h1 class="visually-hidden">
          Hammer Video
        </h1>
        <router-link
          class="link link--with-image"
          :to="{name: 'home'}"
        >
          <img
            class="link__image"
            src="/assets/images/logo-hammer-video.png"
            alt="The Hammer Video Logo"
          >
        </router-link>
      </div>
    </div>
    <button
      class="search-overlay-toggle"
      @click="toggleSearchActive"
    >
      Open search
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