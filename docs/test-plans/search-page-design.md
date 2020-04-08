<!-- Generate a new file using -->
<!-- sed -e "s/\${title}/My story/" -e "s/\${number}/156128780/" -e "s/\${branch}/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search page design

## Related documentation

## Pivotal Story

* [${Search page design}](https://www.pivotaltracker.com/story/show/${171577478})

## Git branch

* [${search-front-end-171577478}](https://github.com/HammerMuseum/hammer-video/tree/${search-front-end-171577478})

## Description

Implement styles for the search results design

Does the implementation pass the test plan?
Does the implementation pass the a11y test plan?

## Requirements to test

Website password

## Test Plan

### Cross browser

#### Desktop

* Go to <https://dev.video.hammer.cogapp.com/search>
* Click on a Filter label to open the filter overlay
* Does the filter list appear?
* Select a filter item.
* Does the result list change?
* Click the cross to remove your selection.
* Does the result list change?
* Click the large cross to close the filter
* Open another filter
* Choose another filter by clicking on it's label
* Does the filter close and the new filter choice open in the overlay?
* Are there any interaction or visual issues / obscured elements while doing to above steps?

#### On mobile

* Go to <https://dev.video.hammer.cogapp.com/search>
* Click on "Show filters" button to open the filter overlay
* Does the list of filter options appear?
* Select a filter option.
* Does the list of filter items appear?
* Select a filter item.
* Does the result list change?
* Click the cross to remove your selection.
* Does the result list change?
* Click the large cross to close the filter.
* Are there any interaction or visual issues / obscured elements while doing to above steps?

#### Using the keyboard on a desktop device

* Can you navigate the page in a logical order using keyboard controls?
* Can you reach all controls and links on the page using only the keyboard?
