<!-- Generate a new file using -->
<!-- sed -e "s/\Program series link/My story/" -e "s/\170919702/156128780/" -e "s/\program-series-link-170919702/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Program series link

This is a template for a specification.

## Pivotal story

[Program series link](https://www.pivotaltracker.com/story/show/170919702)

## Git branch

[program-series-link-170919702](https://github.com/HammerMuseum/hammer-video/program-series-link-170919702)

## Story description

**As a user browsing the archive, I want to see other videos from the same Programs Series**

---

**Acceptance Criteria**
- Does the video page include a link to the program series (when it's part of a series)?


## Implementation
- On each individual page, check if there is a program series
- If there is, add some text and a link with the title of it and the text 'Part of the series'.
- The link should take the user to the search results page filtered by program series.


## Documentation required
