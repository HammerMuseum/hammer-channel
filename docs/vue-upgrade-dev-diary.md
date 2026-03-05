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
  - @TODO figure out how to test embed.js is still working as expected

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
  - Removed 'functional' attribute from `<template>`
  
- Can't remember if there were more filters which needed to be replaced? Seem to have date formatting working with custom code (we only use it in a couple of places and for the same format)
- App.use(VueFilterDateFormat) -- is this just to initialise the plugin? I think so
  - Removed these and everything still seems to work

- _Trying to fix formatting of this file, please ignore_

### Errors and Warnings
#### Homepage

- A lot to do with the Flickity carousel - I'm thinking replacing/updating this library will hopefully solve a lot of these
  - nvm this turned out to be due to the way I was importing the date format function from `date-fns`

- Getting the following: `The global app bootstrapping API has changed: vm.$mount() and the "el" option have been removed. Use createApp(RootComponent).mount() instead.` which seems to be coming from `vue-window-size` which is using the old bootstrapping API. This module will need to be replaced as it hasn't been updated to be compatible with Vue 3. It looks like we're only using it for one small thing, so hopefully quick to replace. Have added to list of modules to replace/update.
NB: This error also coming from here: `The "data" option can no longer be a plain object. Always use a function.`

- `Vue.extend() has been removed in Vue 3. Use defineComponent() instead` coming from `bootstrap-vue`, which we are going to replace.

- `[Vue warn]: (deprecation GLOBAL_PROTOTYPE) Vue.prototype is no longer available in Vue 3. Use app.config.globalProperties instead.` coming from `@vue-ally`, `animated-number-vue`, `bootstrap-vue`*, `vue-announcer`, `vue-check-view`*, `vue-gtm`, `vue-images-loaded`, `vue-progressbar`, `vue-scrollto`, and `vue-window-size`. (* means we're already definitely removing this module anyway). These will all either need to be replaced or updated.

- `Vue 3's render function API has changed. You can opt-in to the new API with: configureCompat({ RENDER_FUNCTION: false })` coming from `portal-vue`, `vue-images-loaded`, `vue-window-size`, `vuetensils`, `animated-number-vue`, and `bootstrap-vue`* (* means we're already definitely removing this module anyway). These will all either need to be replaced or updated.

- v-model usage on component has changed in Vue 3. Component that expects to work with v-model should now use the "modelValue" prop and emit the "update:modelValue" event. You can update the usage and then opt-in to Vue 3 behavior on a per-component basis with `compatConfig: { COMPONENT_V_MODEL: false }`. - coming from `vuetensils` (used for VDrawer in Header)
  - Probably these related too: `(deprecation RENDER_FUNCTION)`, `deprecation OPTIONS_DESTROYED)`, `(deprecation INSTANCE_SCOPED_SLOTS) `, 

- `Attribute "aria-pressed" with v-bind value `false` will render aria-pressed="false" instead of removing it in Vue 3.` - seems to be coming from `bootstrap-vue`, which we're removing anyway.

- `deprecation OPTIONS_DESTROYED` - requires changing to the following:
  ```
  // Vue 2 (Deprecated)
  beforeDestroy() { ... },
  destroyed() { ... },

  // Vue 3 (Replacement)
  beforeUnmount() { ... },
  unmounted() { ... },
  ```
  - Coming from `animated-number-vue`, `bootstrap-vue`*, `focus-trap-vue`, `portal-vue`, `vue-images-loaded`, `vue-window-size`, and `vuetensils`

- **At this point I think I'm just going to start updating/replacing the packages listed so far as it seems that almost all of these warnings are coming from these**

### Plugin updates/replacement

#### Bootstrap Vue
- Bootstrap to be replaced by Matt M - we are only using it in one place (for tabs on the video pages), so we'll just extract the code we need for those and get rid of the package itself.

#### animated-number-vue
- Replaced `animated-number-vue` with a small custom component as there wasn't a good alternative package to replace this with, plus we're only using it in one place and the code is _relatively_ simple (just an animated number counting up). Made new `AnimatedNumber.vue` component. Have removed package.

#### @vue-a11y/announcer + vue-announcer
- Replacing https://www.npmjs.com/package/@vue-a11y/announcer 
  - Can't tell what the difference between `@vue-a11y/announcer` and `vue-announcer` is - link to the same Github page
  - @TODO **Will need to add testing the screenreader announcements to the test plan**
  - Replacing with 'next' version: https://github.com/vue-a11y/vue-announcer/tree/next 
  - @TODO Run npm audit to check this package hasn't introduced sth bad (will do at end to check all packages)
  - Doesn't mention I need to migrate any code
  - Just removed `vue-announcer` as I couldn't see it doing anything...
    - Okay it seems to be broken now... putting vue-announcer back
  - Seems to be working again, but @TODO: The announcement when you load the search page with results seems to be broken on production as well as I never managed to get it to say it, only "Search results page loaded" - should be `Search results for ${term}. Page loaded with ${this.total} results.` - SearchPage.vue
  - Ah, it seems I can remove `vue-announcer` as it's just an older version of this package, I just need to leave `<vue-announcer />` in App.js which I had removed

#### vue-gtm

- @TODO: Add checking GTM is still pushing to data layer to test plan
- Looks like this is the Vue 3 compatible replacement: https://www.npmjs.com/package/@gtm-support/vue-gtm but hopefully shouldn't need any or much code changing
- Added replacement and using this to run GTM - minor tweak to code in app.js
- Put `debug` to `true` so I can see whether it's still working
- NB: On search page it seems there is a different between the results total showing on the page and the total logged by the GTM package in debug mode - will need to compare with production to see if issue from this package or not
  - This difference looks like it's showing me the previous total instead of the updated one - I have a feeling this might be an issue which already exists in the code
- Removed `vue-gtm` and everything seems to be working okay, will need to test more to check it's definitely sending everything to GA

#### vue-images-loaded

- No official update/replacement offered, will break with Vue 3.
- Tested a bunch removing this directive and although it doesn't seem to affect the homepage carousels (they still load when the images have finished loading, with a minor CLS on the featured carousel, which exists on the production site under the same conditions (throttling, no cache) anyway), it does stop the carousel in related content on the video page from loading properly, so we do need to replace this behaviour, which means replacing w/ new package or custom code.
- Idea: wrapping the `imagesloaded` package in a simple custom (V3-compatible) directive (which is what this package also originally did for V2)
- Have added this custom directive `imagesLoaded.js` which wraps the `imagesloaded` package and we are now using this instead of the old package. Have tested and it seems to be behaving mostly the same as production, the only difference is that the carousel on the video page doesn't load/mount until the images have _finished_ loading, whereas on production (with throttling) the carousel appears w/ content, but I can see the images slowly loading line-by-line, old school style. In both cases there is a layout shift where the entire carousel mounts after the page has started loading which isn't ideal, but was original behaviour so not in the scope of this story to fix, but at least now you don't then see all the images loading in slowly.

#### vue-progressbar

- @TODO:  Add checking the progress bar still appears to the test plan
- No official update for or replacement for Vue 3.
- Used a fork for Vue 3 based off of the original package created by a different developer - https://www.npmjs.com/package/@aacassandra/vue3-progressbar.
- Replaced the original package with is one and the existing code seems to function correctly.

#### vue-scroll-to

- @TODO:  Add Scroll to section and scroll to top still work to the test plan.
- Provided one function that was only used in NavigationBar.vue.
- There was another library that provided similar functionality `scroll-into-view` that was already in package.json, compatible with Vue 3 and used elsewhere in the codebase.
- Refactored `NavigationBar.vue` to use `scroll-into-view` instead and have removed the package and it's initialisation from `app.js`.

#### vue2-hammer

- Adds gesture support for some elements on mobile

- We use it for:
  - Swipe up to close 'About' modal/"footer"
  - Swipe up to close 'Search' modal
  - Swipe left to close facets sidebar on search page

- NB: Doesn't seem to work to close the About modal on my phone - which is good because you want to be able to swipe up to scroll the modal content if it continues below the fold without closing the modal entirely. 
  - It seems that either this swipe to close mechanism works, or you can scroll the content of the element. So, for me the search facets sidebar cannot be scrolled, but can be swiped to close, which means the bottom of the sidebar is cut off (and half of the last facet is cut off, but you can still read it), whereas the 'About' modal can be scrolled, but swiping up does not close it. (Tested in Safari and Chrome on iPhone).
  - Found a definite bug where if you open the facets sidebar and close again (by any means), you can no longer scroll the page

- For now going to discuss whether we want to keep this at all because it's not working in half of the places altogether, and where it does work it seems to mess with normal scrolling behaviour
  - Neil agrees we can just remove this now as it's not working as expected anyway

#### vue-check-view

- Allows bottom nav bar on homepage to highlight which section you're on
- Replaced with simple custom directive `v-view` in app.js using IntersectionObserver. No other changes needed.

#### vuetensils

- @TODO:  Verify Search bar and search page looks visually the same and functions correctly.  The search input text fields on the header search and on the search page should display 'search' when unfocused and 'Type something' when they have focus.   `Vskip` functions correctly (tabbing) on the homepage shows 'Skip to content' which skips to content.
- Utility library - provides a bunch of preconfigured Vue components.  
- Previously on version 0.7.12 - which did not support Vue 3.
- Update to version 0.13.3 as versions above 0.10.0 provide Vue 3 support.
- This new version slightly changes the DOM output for `VInput` elements but seems to function identically.
  - The `text` property in the class prop for `VInput` previously targetted the element providing the label.  In the new version the name of this property has been changed to `label`.  We were using this in a few places to provide a `visually-hidden` label, mainly in search related elements and has been updated.  
  - Because of the DOM changes - the focus and blur listeners attached directly to the `VInput` components no longer function as intended due to event bubbling so I have replaced these with custom event listeners on mount.
- Re-enabled `VSkip` which functions identically.
- The package itself still seems to be emitting some warnings due to prop order with `v-bind` being used after `class`.

#### vue-flickity

- Powers the carousels
- Is a Vue-compatible wrapper around the flickity module
- Tried `@toneflix-code/flickity-vue` as a drop-in replacement, but it broke, so decided on a quick (and simpler than expected) custom wrapper component `Flickity.vue`, which allowed me to have to make 0 changes to the way we were using this in templates, and only add 2 lines of CSS to stop the carousel from breaking out of its container on video pages

#### focus-trap-vue

- Used on search page facets panels when using keyboard to access filters (e.g.: clicking on 'Dates', then should be focus-trapped inside of that sub-panel)
- Just needed an update to the package to make it Vue 3 compatible

#### vue-jest

- There are 2 jest tests which rely on this
- Needs updating to `@vue/vue3-jest`
- [Documentation](https://github.com/vuejs/vue-jest?tab=readme-ov-file#installation) says `@vue/vue3-jest@28` for our version of jest
- Ran tests but need to reinstall/update `@vue/test-utils` next in order for tests to work

#### @vue/test-utils

- Has V3 compatible version, but some breaking changes (migration guide here: https://test-utils.vuejs.org/migration/):
  - Migrated `propsData` to `props`
  - Removed `createLocalVue` usage: Both `clippingtool.spec.js` and `about.spec.js` no longer import or use `createLocalVue` or `VueRouter`, aligning with Vue Test Utils v3 where `createLocalVue` is removed.
  - Migrated `mocks` to `global.mocks`
  - Migrated `stubs` to `global.stubs`
    - `about.spec.js` now uses `global.stubs`: `{ RouterLink: RouterLinkStub }` instead of a top-level stubs option.

- Had to add a super basic `tsconfig.json` to stop Jest throwing warnings when running tests - shouldn't make any actual checks
- Getting errors from vuetensils, which will be updated after this, so had to add a temp. fix:
  - Stubbed VInput via global.stubs: { VInput: true } to avoid internal vuetensils runtime errors while still testing your own component’s behavior.
  - @TODO: Undo this when vuetensils is up to date

#### jest-serializer-vue

- Used to serialise Vue components when using Jest's snapshot assertions, _however_, we are not currently using either `toMatchSnapshot` nor `toMatchInlineSnapshot`, so it looks like we don't need this at all.
- I will remove it and check the tests still run as expected
- Got errors re: typescript not being found - it seems `typescript` was a dependency of `jest-serializer-vue`, and `@vue/vue3-jest` requires it, so have installed it separately and now the tests work and pass.

#### vue-content-loader

- This gives us the lighter grey boxes over the featured carousel before the content loads in
- This has a V3 update, but as we're still running in Vue 2 mode, it doesn't work, so this will need to be updated when we switch over (@TODO) - but it should just work (have quickly tested by switching over temporarily)
