<!-- Generate a new file using -->
<!-- sed -e "s/\Search results: filter by year/My story/" -e "s/\168787612/156128780/" -e "s/\filter-by-year-168787612/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search results: filter by year

Specification for adding the ability to filter videos by year.

## Pivotal story

[Search results: filter by year](https://www.pivotaltracker.com/story/show/168787612)

## Git branch

[filter-by-year-168787612](https://github.com/HammerMuseum/hammer-video/filter-by-year-168787612)

## Story description

**As a user I want to be able to filter results by date so that I can find results from a specific year or years**

**Developer notes:**
- Use Elasticsearch aggregations to run this filter.
- Changes may be needed to the way the date fields are stored in Elasticsearch to enable filtering by date.
- Multiple checkboxes control to enable filtering by year

---

**Acceptance Criteria**
- Can search results be narrowed by date using a control on the UI?

## Implementation
- Create a new API endpoint for the filter functionality
    - Should accept the filter type and the value as arguments
    - Should construct params to request similarly to the following:
    
            "aggs": {
                "range": {
                    "date_range": {
                        "field": "date_recorded",
                        "format": "YYYY-MM-DD",
                        "ranges": [
                          {
                            "from": "2000-01-01",
                            "to": "2020-01-01"
                          }
                        ]
                    }
                }
            }
- Add a route for the new endpoint
- In the frontend, add a 'Filter by year' dropdown to the search results template.
    - The dropdown should be populated with the years of each video as options.
- Add a controller action for selecting a year to filter by, which should call the new API endpoint, passing in the year and the original search term.

## Documentation required
Add the new endpoint to `api.md`.
