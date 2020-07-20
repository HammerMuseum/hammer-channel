<template>
  <div
    :class="[
      'container',
      'container--fixed',
      'container--top',
      'header-container',
      { 'overlay--active': overlayActive }]"
  >
    <header class="header">
      <div class="header__content">
        <div class="header__branding">
          <a
            class="link link--with-image"
            href="https://hammer.ucla.edu"
          >
            <img src="/images/logo-hammer-vertical.png">
          </a>
        </div>
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
        <div class="header__actions">
          <button
            class="button button--action button--light overlay-toggle--footer"
            aria-haspopup="true"
            :aria-expanded="overlay.footer"
            @click="overlay.footer = !overlay.footer"
          >
            <span class="button__text">About</span>
            <SvgIcon
              name="menu"
              title="Show information about the archive"
            />
          </button>
          <VDrawer
            v-model="overlay.footer"
            transition="slide-down"
            bg-transition="fade"
            no-scroll
            :classes="{
              bg: 'drawer__container drawer__container--footer',
              content: ['drawer__content', 'drawer__content--footer'] }"
          >
            <template #toggle />
            <TheFooter @close="overlay.footer = false" />
          </VDrawer>

          <button
            class="button button--light overlay-toggle--search"
            aria-haspopup="true"
            :aria-expanded="overlay.search"
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
              <template v-if="overlay.search">
                <use xlink:href="/images/sprite.svg#sprite-close-pink" />
              </template>
              <template v-else>
                <use xlink:href="/images/sprite.svg#sprite-search" />
              </template>
            </svg>
          </button>
          <VDrawer
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
            <SearchBar
              :classes="['search-bar--overlay']"
              focus
              @close="overlay.search = false"
            />
          </VDrawer>
        </div>
      </div>
    </header>
  </div>
</template>

<script>
import { VDrawer } from 'vuetensils/src/components';
import SearchBar from './SearchBar.vue';
import TheFooter from './TheFooter.vue';

export default {
  name: 'TheHeader',
  components: {
    SearchBar,
    TheFooter,
    VDrawer,
  },
  data() {
    return {
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
};
</script>

<style>
.vts-drawer__content {
  height: auto;
}
</style>
