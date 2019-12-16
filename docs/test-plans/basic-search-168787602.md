<!-- Generate a new file using -->
<!-- sed -e "s/\Basic search/My story/" -e "s/\168787602/156128780/" -e "s/\basic-search-168787602/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Basic search

## Related documentation
* [Elastic Search documentation](../search.md) 

## Pivotal Story

* [Basic search](https://www.pivotaltracker.com/story/show/168787602)

## Git branch

* [basic-search-168787602](https://github.com/HammerMuseum/hammer-video/tree/basic-search-168787602)

## Description

Ability to search the initial set of videos.

## Requirements to test
- Access to the dev environment.

## Test Plan
- Navigate to the homepage
- You should initially see a grid of all video thumbnails.
- Type 'Nimoy' in the search box and click 'Search'.
- The page should reload with the video thumbnail for 'Leonard Nimoy' only.
- The title of the video should display beneath the thumbnail.
- The thumbnail/title should link to the individual video page for 'Leonard Nimoy'.
- Search for a random word.
- Unless there is a video with that word in the title, the page should indicate no results.
