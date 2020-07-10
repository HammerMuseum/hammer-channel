<!-- Generate a new file using -->
<!-- sed -e "s/\Better no results presentation/My story/" -e "s/\172858415/156128780/" -e "s/\better-no-results-feedback-172858415/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Better no results presentation

## Related documentation

## Pivotal Story

* [Better no results presentation](https://www.pivotaltracker.com/story/show/172858415)

## Git branch

* [better-no-results-feedback-172858415](https://github.com/HammerMuseum/hammer-video/tree/better-no-results-feedback-172858415)

## Description

When no results are found for a search, show a box of tags to provide some
potential onward journeys.

## Requirements to test

## Test Plan

- Go to stage.video.hammer.cogapp.com/search

- Search for "pineapple" 

- Check that no results are found

- Check that suggested searches pop up.

