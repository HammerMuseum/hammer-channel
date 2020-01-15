<!-- Generate a new file using -->
<!-- sed -e "s/\Search results: filter by year/My story/" -e "s/\168787612/156128780/" -e "s/\filter-by-year-168787612/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search results: filter by year

## Related documentation

## Pivotal Story

* [Search results: filter by year](https://www.pivotaltracker.com/story/show/168787612)

## Git branch

* [filter-by-year-168787612](https://github.com/HammerMuseum/hammer-video/tree/filter-by-year-168787612)

## Description
**As a user I want to be able to filter results by date so that I can find results from a specific year or years**

## Requirements to test
Access to the testing environment.

## Test Plan
- On the homepage, is there a list of all videos?
- From the homepage, type a term in the search box and click 'Search'
    - Are you taken to a listing page displaying all of your results ordered by relevance?
- On this page, you should see a 'Filter by year' option.
- Select a year from the dropdown and click 'Filter'
    - Do the results now shown match your year selection?
- From the same results page, do the same for another year.
    - Do the results show your new selection?
- Repeat for at least one other search term.
