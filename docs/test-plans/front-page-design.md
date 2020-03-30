<!-- Generate a new file using -->
<!-- sed -e "s/\${title}/My story/" -e "s/\${number}/156128780/" -e "s/\${branch}/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Front page design

## Related documentation

## Pivotal Story

* [${Front page design}](https://www.pivotaltracker.com/story/show/${171577479})

## Git branch

* [${home-styling-171577479}](https://github.com/HammerMuseum/hammer-video/tree/${home-styling-171577479})

## Description

Implement styles for the homepage design

Does the implementation pass the test plan?
Does the implementation pass the a11y test plan?

## Requirements to test

Website password

## Test Plan

### Cross browser

* Go to <https://dev.video.hammer.cogapp.com>
* Click on the arrows to navigate through the first carousel
* Is the previous arrow disabled when viewing the first slide?
* Is the next arrow disabled when viewing the last slide?
* Repeat for the carousels that follow below
* Can you navigate through the in the topic list at the bottom of the screen by dragging the navigation bar?
* Are there any obvious visual issues or obscured elements?

### Using the keyboard on a desktop device

* Can you navigate the page in a logical order using keyboard controls?
* Can you reach all controls and links on the page using only the keyboard?
