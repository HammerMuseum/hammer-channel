<!-- Generate a new file using -->
<!-- sed -e "s/\Initial page listing/My story/" -e "s/\169994869/156128780/" -e "s/\listing-page-169994869/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Initial page listing

## Related documentation
n/a

## Pivotal Story

* [Initial page listing](https://www.pivotaltracker.com/story/show/169994869)

## Git branch

* [listing-page-169994869](https://github.com/HammerMuseum/hammer-video/tree/listing-page-169994869)

## Description
**Output all the videos in a grid-layout list onto a webpage on a shareable environment**

Links should be a thumbnail of the video with the title displayed in the vicinity.

## Requirements to test
* Access to the dev environment. https://dev.video.hammer.cogapp.com

## Test Plan - cross browser
- Navigate to the homepage at https://dev.video.hammer.cogapp.com
- You should see a static listing of video thumbnails with titles, un-paginated.
    - n.b The order does not matter.
- Click on any thumbnail or title.
    - You should be directed to the individual page for that video.
    - The title and video on that page should match your expectations from clicking the link.