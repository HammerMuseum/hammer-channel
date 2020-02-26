<!-- Generate a new file using -->
<!-- sed -e "s/\Attend next event drawer/My story/" -e "s/\171253158/156128780/" -e "s/\attend-event-h171253158/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Attend next event drawer

## Related documentation

## Pivotal Story

* [Attend next event drawer](https://www.pivotaltracker.com/story/show/171253158)

## Git branch

* [attend-event-h171253158](https://github.com/HammerMuseum/hammer-video/tree/attend-event-h171253158)

## Description
**As a user, I want to see what's on at the Hammer, so that I can attend a public program in person**

## Requirements to test
Access to the testing environment.

## Test Plan
- Navigate to the main site.
- Click on any video from the homepage.
- On the individual video page:
  - Do you see three 'drawers' at the bottom of the page, with only titles showing for:
    - Topics and tags
    - Related videos
    - Attend our next event
- Click on any of the drawer titles
  - Does the drawer 'open' and show the content inside?
- Click on the 'Attend our next event' drawer
  - Is there a link inside to the hammer website?
- Click the link
  - Are you taken to the Programs & Events page on the Hammer website: https://hammer.ucla.edu/programs-events
