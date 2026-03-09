# Update to Vue 3

## Related documentation

- [Browser testing list]()
- [Test user credentials]()

## Linear story

https://linear.app/cogapp/issue/HAM-47/update-to-vue-3

## Description

Vue 2 end-of-life is December 31st, 2023. 

Upgrade to Vue 3.

## URL(s) to test

https://dev.video.hammer.cogapp.com/

## Requirements to test

Credentials (TBC)

## Test script

### Visual regression

Test at 400px and 1080px screen widths. You can either compare to the live site, or use screenshots taken at these screen widths here: https://drive.google.com/drive/folders/14qD9wwcsi4tZHP_bB0eDHmdMXEj7fs7u?usp=drive_link. 

#### Pass criteria

The pages/components should look and behave exactly the same as the production site, unless otherwise stated.

#### Pages/components to test:

##### Home

- Header (Same on every page, but we can just test here)
  - 'About' modal (Appears as three vertical dots on mobile)
  - Search modal
- Featured carousel (first carousel on page)
- Topic carousels
- Carousel search bar (search box which appears in between 2 carousels some distance down the page)
- Topic nav bar (Sticky nav bar at bottom of page)

##### /search

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

##### /video/{id}/{slug}

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
