<!-- Generate a new file using -->
<!-- sed -e "s/\My story/My story/" -e "s/\156128780/156128780/" -e "s/\resume-playback-171644111/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Resume playback based on last watched time

## Related documentation

## Pivotal Story

- [Resume playback based on last watched time](https://www.pivotaltracker.com/story/show/171644111)

## Git branch

- [resume-playback-171644111](https://github.com/HammerMuseum/hammer-video/tree/resume-playback-171644111)

## Description

## Requirements to test

- Access to the [dev preview](https://hammer:armandvideo6@dev.video.hammer.cogapp.com/) or branch running locally
- Devtools open, monitoring localstorage

## Test Plan

- Watch a video (e.g. [videoId=1775](https://dev.video.hammer.cogapp.com/video/1775/guillermo-del-toro-introduces-his-pinocchio)) for a few seconds then pause it.
  - Does your localstorage have a key format `lastWatched-{videoId}`, and a value equal to the seconds elapsed on your video?
  - Make a mental note of the seconds you've paused at.
- Navigate away from the page, e.g. to a filtered search from the info panel on the right.
- Navigate back to the video you preiviously paused.
- Once the page has loaded, is the video progress bar set to the same time you paused it?
- With the clipping tool / queryparams set a clip that _doesn't_ include time you've paused the video at. (e.g. if you paused at 10 seconds, clip from 20-30 seconds)
- Load the clip's page, is the video primed to play the start of clip (therefore ignoring the time from localstorage)?
- Monitor your local storage whilst you watch the video till the end
  - Once the video has ended, is the `lastWatched-{videoId}` keyval deleted?
- Reload the page (with no query params), does the video start at the very beginning?
