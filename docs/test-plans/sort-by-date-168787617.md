<!-- Generate a new file using -->
<!-- sed -e "s/\All videos page: sort by date/My story/" -e "s/\168787617/156128780/" -e "s/\sort-by-date-168787617/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# All videos page: sort by date

## Related documentation

## Pivotal Story

* [All videos page: sort by date](https://www.pivotaltracker.com/story/show/168787617)

## Git branch

* [sort-by-date-168787617](https://github.com/HammerMuseum/hammer-video/tree/sort-by-date-168787617)

## Description

**As a user I want to be able to sort search results by date so that I can quickly find the most recent content**

## Requirements to test
Access to the testing environment

## Test Plan
- On the homepage, are the videos ordered by date recorded with the most recent first? (To check this, click into the individual videos and see the date beneath the title.)
- From the homepage, type a term in the search box and click 'Search'
    - Are you taken to a listing page displaying all of your results ordered by relevance?
- On this page, you should see a 'Sort by date' filter.
- Select 'Sort by date (ASC)' and click 'Sort'
    - Are the results now ordered by date ascending?
- From the same results page, select 'Sort by date (DESC)' and click 'Sort'.
    - Are the results now ordered by date descending?
- Repeat for at least one other search term.
