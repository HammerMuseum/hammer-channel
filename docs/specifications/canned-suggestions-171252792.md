<!-- Generate a new file using -->
<!-- sed -e "s/\Canned suggestions/My story/" -e "s/\171252792/156128780/" -e "s/\canned-suggestions-171252792/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Canned suggestions

This is a specification for canned search suggestions on the homepage.

## Pivotal story

[Canned suggestions](https://www.pivotaltracker.com/story/show/171252792)

## Git branch

[canned-suggestions-171252792](https://github.com/HammerMuseum/hammer-video/canned-suggestions-171252792)

## Story description

**As a browsing user without a specific search term in mind, I want to see suggested terms guaranteed to provide some results so that I'm not stuck wondering what to search for**

Developer notes:
- Use in_playlists field to get all videos in featured playlist

---
- Is the search pane accessible from every page of the site?
- Does the search pane show a number of suggested search terms?
-- Min 5
-- Max 12
- Are these search terms derived from the videos in the Featured playlists?
-- People
-- Tags
-- Topics
- Are they randomly selected on each request?
- When I click a term, does it take me to the Search Results  page showing the results for this search term?
-- [this is the right thing to do, isn't it? rather than apply filters from various facets for people/tag/topic?]
- From the search results page, can I search for something else?
- From the search results page, can I clear my search and see all the videos?


## Implementation
- Create a new endpoint `/suggestions`
- In the endpoint, fetch featured playlists via the API
- With the result, build a new array of topics and tags
  - The elements in the array should be aware of whether or not they are tags or topics so that the correct filter can be applied in the link to the search results.
- Run php's native `shuffle()` on the array to randomise the order
- Limit the array to 12 with `array_slice($array, 0, 12)`
- Return the array to the `SearchBar` Vue component
- Set the terms as data on the component
- Populate the existing search links element with the terms

## Documentation required
