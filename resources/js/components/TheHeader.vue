<template>
  <div
    :class="[
      'container',
      'container--fixed',
      'container--top',
      'header-container',
      { 'overlay--active': overlayActive }]"
  >
    <VSkip to="#featured">
      Skip To Main Content
    </VSkip>
    <header class="header">
      <div class="header__content">
        <div class="header__branding">
          <a
            class="link link--with-image"
            href="https://hammer.ucla.edu"
            aria-label="Go to the Hammer Museum website"
          >
            <img src="/images/logo-hammer-vertical.png" alt="Hammer Museum Logo">
          </a>
        </div>
        <div class="header__title">
          <RouterLink
            class="link link--with-image"
            aria-label="Go to the homepage"
            :to="{name: 'app'}"
          >
            <h1 class="visually-hidden">
              Hammer Video
            </h1>
            <svg
              title="Hammer video logo"
              class="icon"
            >
              <use xlink:href="/images/sprite.svg#sprite-hammer-video" />
            </svg>
          </RouterLink>
        </div>
        <nav role="navigation" class="header__actions">
          <button
            class="button button--action button--light overlay-toggle--footer"
            :aria-pressed="overlay.footer"
            :aria-expanded="overlay.footer"
            aria-label="Show information about the archive"
            aria-controls="about-overlay"
            @click="overlay.footer = !overlay.footer"
          >
            <span class="button__text">About</span>
            <SvgIcon
              name="menu"
              title="Show information about the archive"
            />
          </button>
          <VDrawer
            id="about"
            v-model="overlay.footer"
            transition="slide-down"
            bg-transition="fade"
            no-scroll
            :classes="{
              bg: 'drawer__container drawer__container--footer',
              content: ['drawer__content', 'drawer__content--footer'] }"
          >
            <template #toggle />
            <button
              :class="['footer__close-button', 'button', 'button--icon']"
              @click="handleFooterClose"
            >
              <SvgIcon
                name="close-pink"
                title="Close the description overlay"
              />
            </button>
            <TheFooter
              v-hammer:swipe.up="handleFooterClose"
              @close="overlay.footer = false"
            />
          </VDrawer>

          <button
            class="button button--light overlay-toggle--search"
            :aria-pressed="overlay.search"
            :aria-expanded="overlay.search"
            aria-label="Search"
            aria-controls="search-overlay"
            @click="overlay.search = !overlay.search"
          >
            <svg
              aria-labelledby="search-icon-title"
              role="img"
              :class="[
                'icon',
                {'icon--search': !overlay.search},
                {'icon--close': overlay.search}
              ]"
            >
              <title
                id="search-icon-title"
                lang="en"
              >{{ overlay.search ? 'Close the search panel' : 'Open the search panel' }}</title>
              <use xlink:href="/images/sprite.svg#sprite-search" />
            </svg>
          </button>
          <VDrawer
            id="search-overlay"
            v-model="overlay.search"
            transition="slide-down"
            bg-transition="fade"
            no-scroll
            :classes="{
              bg: 'drawer__container',
              content: [
                'drawer__content',
                'drawer__content--search',
                'background--grate'
              ]
            }"
          >
            <template #toggle />
            <button
              :class="['footer__close-button', 'button', 'button--icon']"
              @click="handleSearchClose"
            >
              <SvgIcon
                name="close-pink"
                title="Close the description overlay"
              />
            </button>
            <SearchBar
              v-hammer:swipe.up="handleSearchClose"
              :classes="['search-bar--overlay']"
              focus
              :tags="tags"
              @close="overlay.search = false"
            />
          </VDrawer>
        </nav>
      </div>
    </header>
  </div>
</template>

<script>
import axios from 'axios';
import { VDrawer, VSkip } from 'vuetensils/src/components';
import SearchBar from './SearchBar.vue';
import TheFooter from './TheFooter.vue';

export default {
  name: 'TheHeader',
  components: {
    SearchBar,
    TheFooter,
    VDrawer,
    VSkip,
  },
  data() {
    return {
      tags: [],
      title: 'Hammer Video',
      overlay: {
        search: false,
        footer: false,
      },
    };
  },
  computed: {
    overlayActive() {
      return this.overlay.search || this.overlay.footer;
    },
  },
  mounted() {
    this.getTags();
  },
  methods: {
    getTags() {
      axios
        .get('/api/suggestions')
        .then((response) => {
          this.tags = response.data;
        }).catch((err) => {
          this.tags = [];
        });
    },
    handleSearchClose() {
      this.overlay.search = false;
    },
    handleFooterClose() {
      this.overlay.footer = false;
    },
  },
};
</script>

<style>
.vts-drawer__content {
  height: auto;
}
</style>
