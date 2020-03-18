<!-- Generate a new file using -->
<!-- sed -e "s/\Topics and tags filter/My story/" -e "s/\171834504/156128780/" -e "s/\topics-tags-filter-171834504/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Topics and tags filter

This is a specification for combining topics and tags into one filter and displaying with a searchable box in a facet.

## Pivotal story

[Topics and tags filter](https://www.pivotaltracker.com/story/show/171834504)

## Git branch

[topics-tags-filter-171834504](https://github.com/HammerMuseum/hammer-video/topics-tags-filter-171834504)

## Story description

**As a user who has conducted a search, I want to be able to filter my results down by topic and tag so I can see how broadly my search term is relevant in the archive and/or more specifically drill down into the topic or tag I am interested in**

- Is there a topic/tag filter list on the search results page?
- Does the list filter down as I type?
- If I select a filter, does it show only the results with that topic/tag?
- Can I choose more than one filter? 
- Can I clear the filter?
- Are the topics listed at the top of the pane, A-Z?
- Are the tags listed below the topics A-Z?

## Implementation
* The tags aggregation and facet will need to be added to the backend.
* On the frontend, create a new `SearchableFacet` Vue component.
  * This facet will accept an array of facet data objects.
* Convert original facets to individual declarations rather than being inside a loop.
* In the `SearchableFacet` component, process the objects so that they are in a usable format and in alphabetical order.
* Render all topics and tags by name, linked to their facet search.
  * Render topics first
  * Render tags second
  * The box should be scrollable
* Add a search box with a data model for the facets by name.
* When a letter is typed in the box, call a function which filters out all terms that don't contain matches.
* Style this so that the component opens as a box to the right of the facet list.

## Documentation required
