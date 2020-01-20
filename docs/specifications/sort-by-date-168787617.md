<!-- Generate a new file using -->
<!-- sed -e "s/\All videos page: sort by date/My story/" -e "s/\168787617/156128780/" -e "s/\sort-by-date-168787617/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# All videos page: sort by date

Add the ability to sort videos by date.

## Pivotal story

[All videos page: sort by date](https://www.pivotaltracker.com/story/show/168787617)

## Git branch

[sort-by-date-168787617](https://github.com/HammerMuseum/hammer-video/sort-by-date-168787617)

## Story description

**As a user I want to be able to sort search results by date so that I can quickly find the most recent content**

**Developer notes:**
- Elasticsearch can be used to return the ordered results for searches that are then sorted by date
- Date Recorded can be used for ordering all videos page by date

**Acceptance Criteria**
- On the 'all videos' page (where no search has been carried out) are the results sorted by date (most recent first)?
- On a search results page, are the results sorted by Relevance by default? 
- On a search results page, can I switch the results to be ordered by date?

## Implementation
- Update search API's `matchAll` method to sort by `date_recorded` by default.
- Optional URL parameters to search controller's `match` route and method for sort field and direction (ASC/DESC)
- Append sort parameters to Elasticsearch query in `match` method when specified.
- New controller action to frontend search controller for sorting
    - Accepts a sort field and a direction
- New separate template for search results listing
- Sort button in new template for date ascending/descending

## Documentation required
- API documentation for optional parameters
