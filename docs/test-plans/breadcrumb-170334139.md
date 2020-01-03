<!-- Generate a new file using -->
<!-- sed -e "s/\Video page: breadcrumb for 'back to all videos'/My story/" -e "s/\170334139/156128780/" -e "s/\breadcrumb-170334139/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video page: breadcrumb for 'back to all videos'

## Pivotal Story

* [Video page: breadcrumb for 'back to all videos'](https://www.pivotaltracker.com/story/show/170334139)

## Git branch

* [breadcrumb-170334139](https://github.com/HammerMuseum/hammer-video/tree/breadcrumb-170334139)

## Description
**When arriving on a video page is there a link to take me back to the All Videos page?**

## Requirements to test
- Access to the testing environment

## Test Plan
- Navigate to the homepage.
- From the homepage, click on any video.
    - Are you taken to an individual video page?
- Above the video should be a link labelled 'Back to All Videos'
- Click the link
    - Are you returned to the 'All Videos' page?
- Search for a video by title in the search box.
- From the results, click on a video thumbnail.
    - Are you taken to an individual video page?
- Above the video should be a link labelled 'Back to All Videos'
- Click the link
    - Are you returned to the 'All Videos' page?   
