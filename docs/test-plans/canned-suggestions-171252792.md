<!-- Generate a new file using -->
<!-- sed -e "s/\Canned suggestions/My story/" -e "s/\171252792/156128780/" -e "s/\canned-suggestions-171252792/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Canned suggestions

## Related documentation

## Pivotal Story

* [Canned suggestions](https://www.pivotaltracker.com/story/show/171252792)

## Git branch

* [canned-suggestions-171252792](https://github.com/HammerMuseum/hammer-video/tree/canned-suggestions-171252792)

## Description
**As a browsing user without a specific search term in mind, I want to see suggested terms guaranteed to provide some results so that I'm not stuck wondering what to search for**

## Requirements to test
Access to the testing environment

## Test Plan
- Navigate to the homepage
- Click on the search icon in the top-right
  - Is the search overlay opened?
  - Under the search box, do you see a box with the text 'or try' and 12 topics/tags listed?
- Click on one of the topics or tags in the list
  - Are you taken to a filtered search results page for that topic/tag?
- Click on the search icon in the top right again.
  - Do you see a different list of topics / tags to the one you saw before?
