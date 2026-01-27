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
  - Found this: https://stackoverflow.com/questions/78827506/invalid-path-to-chromedriver-when-running-laravel-dusk so installed chrome-driver 126 and now I have a _new_ error:

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
