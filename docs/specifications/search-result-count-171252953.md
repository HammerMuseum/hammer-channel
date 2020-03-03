<!-- Generate a new file using -->
<!-- sed -e "s/\Search result count/My story/" -e "s/\171252953/156128780/" -e "s/\search-result-count-171252953/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search result count

This is a template for a specification.

## Pivotal story

[Search result count](https://www.pivotaltracker.com/story/show/171252953)

## Git branch

[search-result-count-171252953](https://github.com/HammerMuseum/hammer-video/search-result-count-171252953)

## Story description
**As a user doing a search, I want to see at-a-glance how many videos match my search term, so I have an idea of the proliferation of my search term in the archive**

Developer notes:
- If paginated results then text should read: "Showing 1 - 12 of 30 results" on page 1 and "Showing 13 - 24 of 30 results" on page 2 etc.

---
- When I search, does the search results page have a statement at the top: 'Showing X - Y of Z results for "search term"
- Does Z match the total number of videos show on all pages of the search results for that term?

## Implementation
- When running a search via the datastore, take the returned 'total' and pass this back to the frontend to get full results count.
- Add text block level with sort buttons containing text for 'Showing 1-12 of 24' for example (where 24 is the total).
- Determine what should show in place of 1-12 by counting the results returned by the API.
- Populate the text with v-models for the numbers.

## Documentation required
