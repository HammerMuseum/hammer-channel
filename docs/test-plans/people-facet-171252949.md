<!-- Generate a new file using -->
<!-- sed -e "s/\People filter/My story/" -e "s/\171252949/156128780/" -e "s/\people-facet-171252949/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# People filter

## Related documentation

## Pivotal Story

* [People filter](https://www.pivotaltracker.com/story/show/171252949)

## Git branch

* [people-facet-171252949](https://github.com/HammerMuseum/hammer-video/tree/people-facet-171252949)

## Description

## Requirements to test

## Test Plan
- Navigate to the search page `/search`
  - On the left-hand side, do you see a facet 'Speakers'?
- Click on the Speakers facet
  - Does a box open to the right?
  - Is there a visible search bar?
  - Beneath the search bar, is there a list of people?
  - Is the box scrollable?
- Type a letter in the search bar.
  - Does the list filter down based on the letter you typed?
- Continue typing a word
  - Does the list continue to filter down as you type?
  - If your search yielded no results, is that made clear by a message?
- Click on any speaker.
  - Does the box close?
  - Are the search results now filtered by the speaker you selected?
