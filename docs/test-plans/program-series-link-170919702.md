<!-- Generate a new file using -->
<!-- sed -e "s/\Program series link/My story/" -e "s/\170919702/156128780/" -e "s/\program-series-link-170919702/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Program series link

## Related documentation

## Pivotal Story

* [Program series link](https://www.pivotaltracker.com/story/show/170919702)

## Git branch

* [program-series-link-170919702](https://github.com/HammerMuseum/hammer-video/tree/program-series-link-170919702)

## Description

**As a user browsing the archive, I want to see other videos from the same Programs Series**

## Requirements to test
- Access to the testing environment.

## Test Plan
- Navigate to the test site
- From the test site, click 'Search' at the top of the page.
- Filter the results by any program series.
- Click on one of the results.
- On the individual video page:
    - Do you see a 'Part of the series' line above the description with the title of the series you originally filtered by?
- Click on the linked program series title.
    - Are you taken to a filtered results page, displaying all videos from the program series?
- Return to the search page
- Click on a video that is not a part of a program series.
    - Is the 'Part of the series' link now hidden? 

