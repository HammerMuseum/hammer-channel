<!-- Generate a new file using -->
<!-- sed -e "s/\${title}/My story/" -e "s/\${number}/156128780/" -e "s/\${branch}/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video player

## Related documentation

## Pivotal Story

* Part of: https://www.pivotaltracker.com/story/show/171577475

## Git branch

develop

## Description

The layout of the controls on the video has been customised somewhat. The therefore need to
be cross-browser tested on all level 1 and 2 supported browsers including IE11.

## Requirements to test

Credentials to access https://stage.video.hammer.cogapp.com

## Test Plan

- Go to any individual video.
 e.g. https://stage.video.hammer.cogapp.com/video/van-goghs-life-in-provence

- Does the video load when the page opens?

- The video should not autoplay as this behaviour is heavily discouraged and even prevented
by most web browsers.

- Does pressing play button play the video?

- Does pressing pause button pause the video?

- Can this be repeated multiple times and in quick succession without causing any obvious errors?

- Can you skip forward in time by selecting a point on the progress bar?

- Does the video keep playing after the time has been skipped forward?

- Can you change the volume without any issues?

- Can you mute the volume without any issues?

- Can you put the video into fullscreen mode?

- Can you take the video out of fullscreen mode?

- Currently captions are on by default. Can you turn them off using the CC button?

## Special error handling test

- Open the video in a new tab and seek to a position in the middle of the video.
- Pause the video
- Leave the tab open for at least 15 minutes
- Come back to the tab and press play
- If there is an error, the video should reload in the background and continue playback from the same position.