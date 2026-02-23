Hammer Channel Vue 3 Upgrade Dev Diary

Dev: Emily

Getting set up locally:

- Every line of JS had an eslint error (mostly to do with CLRF vs LF line breaks), ran eslint fix to fix these
- Had to update node in the .nvmrc file as it seems that some node packages were updated a few months ago with this newer version of node but the .nvmrc file hadn’t been updated so running `npm install` after `nvm use` was breaking things. I have updated to node 18 and everything seems okay 👍

## Phase 0: Test plan
### Current unit testing:

#### Laravel Dusk
- In /tests/
- Existing:
  - /Browser/Pages/
    - HomePage.php (smoke test, plus checks for `#selector` element)
    - Page.php (tests for `#selector` element)
  - /Browser/
    - VideoPlayerTest.php (commented out)
  - /Feature/
    - PaginationTest.php (actual test - `testCorrectPagerQueriesAreGeneratedFromPageInformation()`)
- Tried running the tests via `php artisan dusk --env=local` as written in docs and got error: `RuntimeException: Invalid path to Chromedriver`
  - Tried installing Chromedriver via command it suggests (after sshing into ddev): `php artisan dusk:chrome-driver` – no luck
  - Found this: https://stackoverflow.com/questions/78827506/invalid-path-to-chromedriver-when-running-laravel-dusk so installed chrome-driver 126 and now I have a *new* error:
  ```
  Facebook\WebDriver\Exception\Internal\WebDriverCurlException: Curl error thrown for http POST to /session with params: {"capabilities":{"firstMatch":[{"browserName":"chrome","goog:chromeOptions":{"args":["--disable-gpu","--headless","--window-size=1920,1080"]}}]},"desiredCapabilities":{"browserName":"chrome","platform":"ANY","goog:chromeOptions":{"args":["--disable-gpu","--headless","--window-size=1920,1080"]}}}

  Failed to connect to localhost port 9515 after 0 ms: Couldn't connect to server
  ```
  - I'm wondering how useful it is to get these running as a) it looks like no one uses them and b) I could manually test the few things they test?
    - I did find this PR from 1.5 years ago where Neil had maybe the same issue when running these tests on circle ci and he just commented out the tests: https://github.com/HammerMuseum/hammer-channel/pull/167

#### Jest
- Seems there are also a couple of **jest** tests (nothing in docs I could find in project about this apart from the command to run them in package.json)
  - /tests/JavaScript
    - about.spec.js (checks for displaying topics, people, playlists, description, and works without a description)
    - clippingtool.spec.js (checks it displays clipping tool tips, emits an event when user sets start time via button, emits an event when user sets end time via button)
  - Have run these via `npm run test` and these passed

### Visual Regression

- Want to take screenshots for visual regression testing, checking which breakpoints we use
  - No 'variables' used for consistent breakpoints it seems in the CSS, but looks like 37.5em (500px), 60em (1080px), 86em are the 'layout' ones
  - 60em seems to be where a lot of significant shifts happen, so I think get screenshots using **60em** and a mobile size below **37.5em**

#### What pages/components exist?
  - Home
    - 'About' modal (on every page)
    - Featured carousel
    - Topic carousels
    - Carousel search bar
    - Topic nav bar
    - Search dropdown modal
    - Header
  - /search
    - Search header/title/results #/facets selected/ etc
    - Sidebar
      - Search input
      - Filters
        - Filter facets pullout
        - Facets search input
      - Results cards
      - Pagination
      - Sort dropdown
      - No results text
      - "Try" suggestions box
  - /video/{id}/{slug}
    - Video embed
    - Breadcrumbs
    - Header bits
    - Tabbed panel
      - Info
        - Info box + text
      - Transcript
        - Timer links, transcript text, search form, download link, 'up' button
      - Clip
        - Start and end times buttons
        - Link + copy
      - Share
        - Share/cite buttons
        - Citation box w/ button
      - Related
        - Carousel
        - "try" suggestions box
  - Error

#### Pages and states to screenshot

Use the production site
Each at 400px wide and 1080px wide

Mobile (400px):
- Home
  - [x] As is
  - [x] w/ 'About' modal open
  - [x] w/ 'Search' modal open
- Video - https://channel.hammer.ucla.edu/video/1953/roxane-gay-on-the-portable-feminist-reader
  - [x] w/ info open
  - [x] w/ transcript
  - [x] w/ transcript scrolled down (shows 'up' arrow)
  - [x] w/ transcript w/ search open
  - [ ] w/ transcript w/ no transcript loaded
  - [x] w/ clip open
  - [x] w/ clip open w/ a link
  - [x] w/ share open w/ cite section visible
  - [x] w/ related open (w/ carousel + 'try' section)
  - [ ] w/ related open w/o related carousel
- Search
  - [x] w/ no params (all results)
  - [x] w/ search term + facet selected
  - [x] w/ no returned results
  - [x] w/ 'sort' open
  - [x] w/ facets panel open
  - [x] w/ facets panel open, one selected (e.g.: topics and tags)

1080px:
- Home
  - [x] As is
  - [x] w/ 'About' modal open
  - [x] w/ 'Search' modal open
- Video - https://channel.hammer.ucla.edu/video/1953/roxane-gay-on-the-portable-feminist-reader
  - [x] w/ info open
  - [x] w/ transcript
  - [x] w/ transcript scrolled down (shows 'up' arrow)
  - [x] w/ transcript w/ search open
  - [x] w/ transcript w/ search open + search suggestion
  - [ ] w/ transcript w/ no transcript loaded
  - [x] w/ clip open
  - [x] w/ clip open w/ a link
  - [x] w/ share open w/ cite section visible
  - [x] w/ related open (w/ carousel + 'try' section)
  - [ ] w/ related open w/o related carousel
- Search
  - [x] w/ no params (all results)
  - [x] w/ search term + facet selected
  - [x] w/ no returned results
  - [x] w/ 'sort' open
  - [x] w/ facets panel open
  - [x] w/ facets panel open, one selected (e.g.: topics and tags)


  - Note: On this screensize, the filter panel often doesn't appear (plus no 'open' button), until I refresh the page - might be to do with the screenshot mechanism

## Phase 1: Preparation

### Installing migration build

- Updated vue loader to ^16.0.0
- Tried:
```
"dependencies": {
-  "vue": "^2.6.12",
+  "vue": "^3.1.0",
+  "@vue/compat": "^3.1.0"
   ...
},
"devDependencies": {
-  "vue-template-compiler": "^2.6.12"
+  "@vue/compiler-sfc": "^3.1.0"
}
```

but got dependency compatibility issues re: @vue/test-utils; as we're using this for tests I've just commented out the 2 tests and uninstalled the package for now.

- Got more compatibility issues, so ran `npm install --legacy-peer-deps` for now ¯\_(ツ)_/¯

### Updating webpack

- Updating webpack.mix.js as per instructions
- Ran `npm run dev` and got this error: 
```
  Additional dependencies must be installed. This will only take a moment.

  Running: npm install vue-template-compiler --save-dev --legacy-peer-deps

  Finished. Please run Mix again.
```
And it installed `vue-template-compiler` again which I'd removed as part of the last step.
- Looks like it was because these lines needed to say 'version: 3':
```
mix.js('resources/js/app.js', 'public/js/app.js').vue({ version: 2 });
mix.js('resources/js/embed.js', 'public/js/embed.js').vue({ version: 2 });
```
Now it seems to run (with a lot of errors tho, which is expected).

### *Now* installed vue-router@^4.2.0

- Updated `resources/js/router/index.js`:
From:
```javascript
import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../components/HomeComponent.vue";
// ...

Vue.use(VueRouter);

const routes = [
  // ...
];

export default new VueRouter({
  mode: "history",
  routes,
  // ...
});
```

To:

```javascript
import { createRouter, createWebHistory } from "vue-router";
import Home from "../components/HomeComponent.vue";
// ...

const routes = [
  // ...
];

export default createRouter({
  history: createWebHistory(),
  routes,
  // ...
});
```
- Also had to update `path: '*'` to `path: '/:pathMatch(.*)*'` as per: https://router.vuejs.org/guide/migration/#Removed-star-or-catch-all-routes

### Update Main App Initialisation

- In `resources/js/app.js` + `resources/js/embed.js`
  - Switched `new Vue` to `createApp()`

- In `resources/js/store.js`:
  - `const store = Vue.observable()` -> `const store = reactive()`

## Phase 3: Code Migration

### Replacing filters

- Created `resources/js/filters.js`
  - Added `formatDate()`, `filterId()`, `anchorLink()`, `capitalize()`

- In `resources/js/components/video/Video.vue`
  - Imported `formatDate()`, updated line 25

- In `resources/js/components/SearchPage.vue`
  - Imported `formatDate()`, updated line 342

- In `resources/js/components/Pagination.vue`
  - Imported `capitalize()`, realised this filter was never used here anyway lol. Removed it entirely from the file.

- In `resources/js/components/HomeComponent.vue`
  - `filterId()` also never used in this file so just removed.

- In `resources/js/components/Carousel.vue`
  - Imported `filterId()`, updated lines 3 and 8

- In `resources/js/components/NavigationBar.vue`
  - Both filters `filterId` and `anchorLink` weren't being used

- In `resources/js/components/NavigationBarLink.vue`
  - Imported `filterId()` and `anchorLink()`, replaced chained filter with `computed` `return anchorLink(filterId(this.href));`, updated line 3
  - ACTUALLY realised this entire component isn't used...


- Not getting any content on the fe
- Commented out any progress bar stuff because it needs replacing and it was erroring in the console
- Fixed a missing 'h'
- Started fixing issues when running `npm run dev`
  - Removed 'functional' attribute from <template>
  
- Can't remember if there were more filters which needed to be replaced? Seem to have date formatting working with custom code (we only use it in a couple of places and for the same format)
- App.use(VueFilterDateFormat) -- is this just to initialise the plugin? I think so
  - Removed these and everything still seems to work

### Errors and Warnings
#### Homepage

- Feature flag __VUE_PROD_HYDRATION_MISMATCH_DETAILS__ is not explicitly defined: https://link.vuejs.org/feature-flags
- (deprecation GLOBAL_MOUNT) The global app bootstrapping API has changed: vm.$mount() and the "el" option have been removed. Use createApp(RootComponent).mount() instead.
  Details: https://v3-migration.vuejs.org/breaking-changes/global-api.html#mounting-app-instance
- (deprecation OPTIONS_DATA_FN) The "data" option can no longer be a plain object. Always use a function.
  Details: https://v3-migration.vuejs.org/breaking-changes/data-option.html
- (deprecation GLOBAL_EXTEND) Vue.extend() has been removed in Vue 3. Use defineComponent() instead.
  Details: https://vuejs.org/api/general.html#definecomponent
- (deprecation GLOBAL_PROTOTYPE) Vue.prototype is no longer available in Vue 3. Use app.config.globalProperties instead.
  Details: https://v3-migration.vuejs.org/breaking-changes/global-api.html#vue-prototype-replaced-by-config-globalproperties
- (deprecation FILTERS) filters have been removed in Vue 3. The "|" symbol will be treated as native JavaScript bitwise OR operator. Use method calls or computed properties instead.
  Details: https://v3-migration.vuejs.org/breaking-changes/filters.html
- (deprecation RENDER_FUNCTION) Vue 3's render function API has changed. You can opt-in to the new API with:

  configureCompat({ RENDER_FUNCTION: false })

  (This can also be done per-component via the "compatConfig" option.)
  Details: https://v3-migration.vuejs.org/breaking-changes/render-function-api.html 
  at <App>
- (deprecation RENDER_FUNCTION) (2) 
  at <VueAnnouncer> 
  at <App> 
  at <App>
- (deprecation COMPONENT_V_MODEL) v-model usage on component has changed in Vue 3. Component that expects to work with v-model should now use the "modelValue" prop and emit the "update:modelValue" event. You can update the usage and then opt-in to Vue 3 behavior on a per-component basis with `compatConfig: { COMPONENT_V_MODEL: false }`.
  Details: https://v3-migration.vuejs.org/breaking-changes/v-model.html 
  at <VDrawer id="about-overlay" modelValue=false onUpdate:modelValue=fn  ... >
- (deprecation ATTR_FALSE_VALUE) Attribute "aria-pressed" with v-bind value `false` will render aria-pressed="false" instead of removing it in Vue 3. To remove the attribute, use `null` or `undefined` instead. If the usage is intended, you can disable the compat behavior and suppress this warning with:

  configureCompat({ ATTR_FALSE_VALUE: false })

  Details: https://v3-migration.vuejs.org/breaking-changes/attribute-coercion.html 
  at <TheHeader> 
  at <App> 
  at <App>
- (deprecation ATTR_FALSE_VALUE) Attribute "aria-expanded" with v-bind value `false` will render aria-expanded="false" instead of removing it in Vue 3. To remove the attribute, use `null` or `undefined` instead. If the usage is intended, you can disable the compat behavior and suppress this warning with:

  configureCompat({ ATTR_FALSE_VALUE: false })

  Details: https://v3-migration.vuejs.org/breaking-changes/attribute-coercion.html 
  at <TheHeader> 
  at <App> 
  at <App>
- (deprecation RENDER_FUNCTION) (3) 
  at <VDrawer id="about-overlay" transition="slide-down" bg-transition="fade"  ... > 
  at <TheHeader> 
  at <App> 
  at <App>
- (deprecation OPTIONS_DESTROYED) `destroyed` has been renamed to `unmounted`. 
  at <VDrawer id="about-overlay" transition="slide-down" bg-transition="fade"  ... > 
  at <TheHeader> 
  at <App> 
  at <App>
- (deprecation INSTANCE_SCOPED_SLOTS) vm.$scopedSlots has been removed. Use vm.$slots instead.
  Details: https://v3-migration.vuejs.org/breaking-changes/slots-unification.html 
  at <VDrawer id="about-overlay" transition="slide-down" bg-transition="fade"  ... > 
  at <TheHeader> 
  at <App> 
  at <App>
- (deprecation OPTIONS_DESTROYED) (2) 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< undefined > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
- (deprecation COMPONENT_FUNCTIONAL) Functional component <ContentLoader> should be defined as a plain function in Vue 3. The "functional" option has been removed. NOTE: Before migrating to use plain functions for functional components, first make sure that all async components usage have been migrated and its compat behavior has been disabled.
  Details: https://v3-migration.vuejs.org/breaking-changes/functional-components.html 
  at <Loader key=0 class="carousel--full-width" > 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< undefined > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
- (deprecation ATTR_FALSE_VALUE) Attribute "animate" with v-bind value `false` will render animate="false" instead of removing it in Vue 3. To remove the attribute, use `null` or `undefined` instead. If the usage is intended, you can disable the compat behavior and suppress this warning with:

  configureCompat({ ATTR_FALSE_VALUE: false })

  Details: https://v3-migration.vuejs.org/breaking-changes/attribute-coercion.html 
  at <ContentLoader class="content-loader" height=153 width=400  ... > 
  at <Loader key=0 class="carousel--full-width" > 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< undefined > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
-  (deprecation OPTIONS_BEFORE_DESTROY) `beforeDestroy` has been renamed to `beforeUnmount`. 
  at <Carousel id="art" controls=true title="Art"  ... > 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< Proxy(Object) {getFeatured: ƒ, getPageData: ƒ, seeAllLinkText: ƒ, viewHandler: ƒ, …} > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
- (deprecation OPTIONS_BEFORE_DESTROY) (2) 
  at <Flickity ref="carousel" class="carousel" aria-labelledby="artheading"  ... > 
  at <Carousel id="art" controls=true title="Art"  ... > 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< Proxy(Object) {getFeatured: ƒ, getPageData: ƒ, seeAllLinkText: ƒ, viewHandler: ƒ, …} > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
- Method "format" has type "undefined" in the component definition. Did you reference the function correctly? 
  at <CarouselSlide key="1930" item= {asset_id: 1930, title: 'John Walsh on Cézanne and the Impressionists', description: '<p><span data-sheets-root="1">In the first of a th…ncent Van Gogh and Rembrandt van Rijn.</span></p>', thumbnail_url: 'https://hammer.assetbank-server.com/assetbank-hammer/servlet/display?file=22137999f120085888.jpg', title_slug: 'john-walsh-on-cezanne-and-the-impressionists', …} > 
  at <Flickity ref="carousel" class="carousel" aria-labelledby="artheading"  ... > 
  at <Carousel id="art" controls=true title="Art"  ... > 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< Proxy(Object) {getFeatured: ƒ, getPageData: ƒ, seeAllLinkText: ƒ, viewHandler: ƒ, …} > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
- Method "format" has type "undefined" in the component definition. Did you reference the function correctly? 
  at <CarouselSlide key="1929" item= {asset_id: 1929, title: 'Voices of the Diaspora 2025: Porfirio Gutiérrez, Danielle Shang, Paul Mpagi Sepuya & more', description: '<p><span data-sheets-root="1">In an afternoon sess…riched future for generations to come.</span></p>', thumbnail_url: 'https://hammer.assetbank-server.com/assetbank-hammer/servlet/display?file=22137999f1200b6698.jpg', title_slug: 'voices-of-the-diaspora-2025-porfirio-gutierrez-danielle-shang-paul-mpagi-sepuya-more', …} > 
  at <Flickity ref="carousel" class="carousel" aria-labelledby="artheading"  ... > 
  at <Carousel id="art" controls=true title="Art"  ... > 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< Proxy(Object) {getFeatured: ƒ, getPageData: ƒ, seeAllLinkText: ƒ, viewHandler: ƒ, …} > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
- Method "format" has type "undefined" in the component definition. Did you reference the function correctly? 
  at <CarouselSlide key="1928" item= {asset_id: 1928, title: 'Coltrane on Coltrane: An Oral History', description: '<p><span data-sheets-root="1">Vocalist Michelle Co…Alice Coltrane, Monument Eternal</em>.</span></p>', thumbnail_url: 'https://hammer.assetbank-server.com/assetbank-hammer/servlet/display?file=22137999f1200b5888.jpg', title_slug: 'coltrane-on-coltrane-an-oral-history', …} > 
  at <Flickity ref="carousel" class="carousel" aria-labelledby="artheading"  ... > 
  at <Carousel id="art" controls=true title="Art"  ... > 
  at <Home onVnodeUnmounted=fn<onVnodeUnmounted> ref=Ref< Proxy(Object) {getFeatured: ƒ, getPageData: ƒ, seeAllLinkText: ƒ, viewHandler: ƒ, …} > > 
  at <RouterView ref="routerView" > 
  at <BaseTransition appear=false persisted=false mode=undefined  ... > 
  at <Transition name="fade" > 
  at <App> 
  at <App>
- 
