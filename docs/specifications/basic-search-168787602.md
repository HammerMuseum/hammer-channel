<!-- Generate a new file using -->
<!-- sed -e "s/\Basic search/My story/" -e "s/\168787602/156128780/" -e "s/\basic-search-168787602/`git_current_branch`/g" spec-template.md | tee "`git_current_branch`.md" -->

# Basic search

Implementation of a simple search box, connected to Elastic Search.

## Pivotal story

[Basic search](https://www.pivotaltracker.com/story/show/168787602)

## Git branch

[basic-search-168787602](https://github.com/HammerMuseum/hammer-video/basic-search-168787602)

## Story description

**Ability to search the initial set of videos**

---

- Is there a search box on the listing page?
- Can I enter a term from a video title and that video (and any others with titles featuring the search terms) appear as a search result?
 - [Easier to filter this down, go to a results page, reload the listings page?]
- Does each result display a title for the video?
- Does each result have a link to the video?

## Implementation

- Add an input box to the existing listing page.
- Install the Elastic Search PHP wrapper
- Write a class for handling Elastic Search searches and results.
    - This should be a very basic implementation that searches on the title.
- Handle the form submit for the search box to call a controller that uses the above class to search.
- Send the data from the search to the template on the frontend.
- Elastic Search details should be added to the `.env` file.

## Documentation required
- Some simple documentation for the Elastic Search implementation.
