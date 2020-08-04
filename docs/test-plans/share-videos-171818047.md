<!-- Generate a new file using -->
<!-- sed -e "s/\Social sharing metadata/My story/" -e "s/\171818047/156128780/" -e "s/\share-videos-171818047/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Social sharing metadata

## Related documentation

## Pivotal Story

* [Social sharing metadata](https://www.pivotaltracker.com/story/show/171818047)

## Git branch

* [share-videos-171818047](https://github.com/HammerMuseum/hammer-video/tree/share-videos-171818047)

## Description

## Requirements to test

## Test Plan

### Twitter sharing

Go to https://cards-dev.twitter.com/validator

Use the url:

  https://dev.video.hammer.cogapp.com/video/246/black-women-in-media-race-and-gender-inequality-in-hollywood

Check that a video play icon is shown.

Check that you can play the video when pressing play

Now try with a clipped video url.

Note the "start" and "end" parameters. These define the start and end seconds of the clipped video.

  https://dev.video.hammer.cogapp.com/video/246/black-women-in-media-race-and-gender-inequality-in-hollywood?start=1000&end=1180

Paste in to the validator and check that the video shown is a 3 minute clipped section from the main video.

Check playback.

Check that controls function on the video player.

### Facebook sharing

Go to

  https://developers.facebook.com/tools/debug

Log in as Adooki Test user is needed

Use the same URLs as listed above.

Report back any errors found by the debugger.

I am unsure how videos are supposed to be represented in a Facebook feed / whether they are playable etc - any feedback appreciated.
