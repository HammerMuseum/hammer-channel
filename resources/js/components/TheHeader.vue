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

<style>
img {
  max-width: 100%;
  height: auto;
  width: auto;
}

.header {
  position: relative;
  height: 72px;
  display: flex;
  align-content: center;
  justify-content: center;
  background: var(--header-background);
}

.header__title {
  display: inline-flex;
  align-items: center;
  z-index: 1;
}

.header__logo {
  display: none;
  position: absolute;
  left: 0;
  top: 0;
  width: 138px;
  z-index: 0;
}

.link--with-image,
.link__image {
  display: block;
}

@media screen and (min-width: 600px) {
  .header__logo {
    display: block;
  }
}
</style>
