<!-- Generate a new file using -->
<!-- sed -e "s/\Listings page layouts/My story/" -e "s/\168787613/156128780/" -e "s/\listings-page-layouts-168787613/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Listings page layouts

## Related documentation

## Pivotal Story

* [Listings page layouts](https://www.pivotaltracker.com/story/show/168787613)

## Git branch

* [listings-page-layouts-168787613](https://github.com/HammerMuseum/hammer-video/tree/listings-page-layouts-168787613)

## Description

**Acceptance criteria**
- On a listings page, are the videos displayed in a grid by default on desktop and listing on mobile?

## Requirements to test

Http authentication password

## Test Plan

- Go to stage.video.hammer.cogapp.com in a desktop browser
- Can you see a listing of videos?
- Is the listing showing a grid with three items across?

- Go to stage.video.hammer.cogapp.com on a mobile device or mobile browser
- Can you see a listing of videos?
- Is the listing shown in a stacked column one item wide?
