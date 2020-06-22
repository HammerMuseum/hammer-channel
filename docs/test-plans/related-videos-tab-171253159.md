<!-- Generate a new file using -->
<!-- sed -e "s/\Related videos tab/My story/" -e "s/\171253159/156128780/" -e "s/\related-videos-tab-171253159/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Related videos tab

## Related documentation

## Pivotal Story

* [Related videos tab](https://www.pivotaltracker.com/story/show/171253159)

## Git branch

* [related-videos-tab-171253159](https://github.com/HammerMuseum/hammer-video/tree/related-videos-tab-171253159)

## Description

Navigating to a another video via the related videos carousel.

## Requirements to test

Dev site credentials

## Test Plan

Go to https://dev.hammer.video.cogapp.com

Go to any video by clicking on the thumbnail

Click on "Related", some other videos should be shown (If no related items are present then go back and choose another video)

In the video player turn on English captions by clicking on the CC icon.

Play or seek to a point in the video with a visible subtitle caption.

Now choose one of the other videos items from the related items carousel.

The page title, date, description, tags etc should update.

The video player should reload with the chosen video.

Captions data should update to match the new video source.
