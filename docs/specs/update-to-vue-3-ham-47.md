# Vue 3 Migration

Branch name: update-to-vue-3-ham-47
Linear: https://linear.app/cogapp/issue/HAM-47/update-to-vue-3

## Description

Vue 2 end-of-life is December 31st, 2023.

While security risks are low, as a minimum we should be on version 2.7 (we're currently on 2.6.12)

## Phase 0: Test Plan

- [ ] Check through site to see what Vue components there are and where they're used, and which pages exist
  - [ ] Take screenshots of pages as site is currently
  - [ ] Run current tests to make sure they work as is now
  - [ ] Identify any potential weaker places/places which code might break if something Vue-related is changed
- [ ] Write a test plan using this info

## Phase 1: Preparation

- [ ] Create migration branch: `git checkout -b vue3-migration`
- [ ] Install Vue 3 migration build: `npm install vue@^3.4.0 @vue/compat@^3.4.0`
- [ ] Install Vue Router 4: `npm install vue-router@^4.2.0`

## Phase 2: Core Dependencies

- [ ] Update `webpack.mix.js` to use Vue 3
- [ ] Add Vue compatibility alias in webpack config
- [ ] Update `resources/js/router/index.js` to Vue Router 4
- [ ] Update `resources/js/app.js` to use `createApp()`
- [ ] Update `resources/js/embed.js` to use `createApp()`
- [ ] Update `resources/js/store.js` (Vue.observable → reactive)

## Phase 3: Code Migration

### Filters to Replace

- [ ] `resources/js/components/video/Video.vue` - dateFormat filter
- [ ] `resources/js/components/SearchPage.vue` - dateFormat filter
- [ ] `resources/js/components/Pagination.vue` - capitalize filter
- [ ] `resources/js/components/HomeComponent.vue` - filterId filter
- [ ] `resources/js/components/Carousel.vue` - filterId filter
- [ ] `resources/js/components/NavigationBar.vue` - filterId, anchorLink filters
- [ ] `resources/js/components/NavigationBarLink.vue` - filterId, anchor filters (chained)

### Create Filter Utilities

- [ ] Create `resources/js/utils/filters.js` with utility functions
- [ ] Import and use filter utilities in all components

### Lifecycle Hooks

- [ ] Update `destroyed` → `unmounted` in Video.vue
- [ ] Update `beforeDestroy` → `beforeUnmount` in SearchPage.vue
- [ ] Update `destroyed` → `unmounted` in HomeComponent.vue
- [ ] Search entire codebase for other lifecycle hook usages

### Bootstrap Vue

- [ ] Decide: BootstrapVue Next or custom components (Luke suggested normal Bootstrap)
- [ ] Update imports if using BootstrapVue Next
- [ ] Test all Bootstrap Vue components (BTabs, BTab, etc.)

### Plugin Updates

- [ ] Check/update `vue-gtm` → `@gtm-support/vue-gtm` or alternative
- [ ] Check/update `vue-progressbar` → alternative or custom
- [ ] Check/update `vue-scrollto` → Vue 3 version
- [ ] Check/update `vue2-hammer` → `@vueuse/gesture` or direct hammerjs
- [ ] Remove `vue-filter-date-format` (replaced with utilities)
- [ ] Verify `@vue-a11y/announcer` Vue 3 compatibility
- [ ] Check/update `vue-check-view` → Vue 3 version or `@vueuse/core`
- [ ] Verify `vuetensils` Vue 3 compatibility
- [ ] Check/update `vue-flickity` → Vue 3 version
- [ ] Check/update `vue-window-size` → Vue 3 version

## Phase 4: Testing

### Functionality Tests

- [ ] Home page loads
- [ ] Search works
- [ ] Video player loads and plays
- [ ] Video tabs (Info, Transcript, Clip, Share, Related) work
- [ ] Navigation works
- [ ] Search filters/facets work
- [ ] Pagination works
- [ ] Date formatting displays correctly
- [ ] Overlays/modals work
- [ ] GTM tracking fires
- [ ] Router navigation works
- [ ] No console errors
- [ ] No Vue warnings

### Browser Tests

- [ ] Use browser matrix

### Performance

- [ ] Bundle size acceptable
- [ ] Initial load time acceptable
- [ ] Route transitions smooth

### Unit Tests

- [ ] Update existing test setup for Vue 3 (there aren't many)
- [ ] All tests pass
- [ ] Update test utilities if needed

## Phase 5: Cleanup

- [ ] Remove `@vue/compat` from dependencies
- [ ] Remove compatibility mode from app.js
- [ ] Remove Vue compatibility alias from webpack
- [ ] Remove unused dependencies (vue-template-compiler, etc.)
- [ ] Update README/documentation
- [ ] Code review
- [ ] Merge to main branch
