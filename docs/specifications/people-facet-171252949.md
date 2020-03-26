<!-- Generate a new file using -->
<!-- sed -e "s/\Topics and tags filter/My story/" -e "s/\/156128780/" -e "s/\people-facet-171252949/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# People filter

This is a template for a specification.

## Pivotal story

[People filter with search box](https://www.pivotaltracker.com/story/show/171252949)

## Git branch

[people-facet-171252949](https://github.com/HammerMuseum/hammer-video/people-facet-171252949)

## Story description
**As a user who has conducted a search, I want to be able to filter my results down by people so I can see how broadly my search term is relevant in the archive and/or more specifically drill down into the people I am interested in**

- Is there a people filter list on the search results page?
- Does the list filter down as I type?
- If I select a filter, does it show only the results with that person?

## Implementation
- Use the existing SearchableFacet component
- Pass in `facets.people`.

## Documentation required
