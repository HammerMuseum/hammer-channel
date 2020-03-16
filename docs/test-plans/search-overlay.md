<!-- Generate a new file using -->
<!-- sed -e "s/\${title}/My story/" -e "s/\${number}/156128780/" -e "s/\${branch}/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search overlay

## Related documentation

## Pivotal Story

* [${Search overlay}](https://www.pivotaltracker.com/story/show/${171252784})

## Git branch

* [${search-overlay-171252784}](https://github.com/HammerMuseum/hammer-video/tree/${search-overlay-171252784})

## Description

**As a user, I want to enter a search term to narrow down my view of the archive**

Developer notes:

* This is a modal type Vue component that will appear when clicking on the search icon.
* Consider how this works for users with assistive technology. I.e. give focus to the modal when opens and then return focus to the initial location on close.

---

* Is there a search icon in the top right hand corner of every page on the archive?
* When I click the icon, does an overlay appear?
* Can I enter a search in the box on the overlay?
* When I submit the search, does it take me to a search results page?
* Can I close the overlay with the keyboard?
* Can I close the overlay with the escape key?
* Is focus trapped in the overlay when it is active?

## Requirements to test

Website password

## Test Plan

**Cross browser**
* Go to <https://dev.video.hammer.cogapp.com>
* Is there a search icon in the top right hand corner of the page?
* When you click the icon, does a "search box overlay" appear?
* When you click the icon again, does a "search box overlay" hide?
* Click the icon to open the search overlay again.
* Enter the term "politics" into the box and press enter.
* Does the search overlay hide from view?
* Does it take you to a search results page? (url should be /search?term=politics)

* Go to <https://dev.video.hammer.cogapp.com>
* Open the icon to open the search overlay.
* Click on one of the suggested searches.
* Does it take you to a search results page with the filter for the term that you clicked on already applied?

**Now using keyboard navigation on desktop**
* Can you select the search icon in the right hand corner using the tab key?
* Can you activate the search overlay using the enter key?
* Can you now tab through all the elements in the search overlay?
* Is keyboard focus trapped in the search overlay while it is active?
* Can you close the search overlay using the close button?
* Can you close the overlay with the escape key?
* Can you reopen the search overlay and make a query using only keyboard controls?
