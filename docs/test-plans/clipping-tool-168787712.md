<!-- Generate a new file using -->
<!-- sed -e "s/\Clipping tool/My story/" -e "s/\168787712/156128780/" -e "s/\clipping-tool-168787712/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Clipping tool

## Related documentation

## Pivotal Story

* [Clipping tool](https://www.pivotaltracker.com/story/show/168787712)

## Git branch

* [clipping-tool-168787712](https://github.com/HammerMuseum/hammer-video/tree/clipping-tool-168787712)

## Description
**As a user, I want to be able clip a Hammer video so that I can share it on social media or share it with a friend/contact**

## Requirements to test
Access to the testing environment.

## Test Plan
- From the homepage, click on a video thumbnail.
- From the individual video page, click the 'Clip' tab on the left.
  - Does a panel open containing the clipping tool controls?
- Play the video.
- Seek to any point in the video.
- In the clip panel, click 'Set start time'.
  - Is the input populated with the start time in hours, minutes, seconds?
- Seek to a later part of the video and click 'Set end time'
  - Is the end time input populated with the end time in hours, minutes, seconds? 
- Click 'Generate shareable link'.
  - Is the field below populated with a link?
- Click 'Copy to clipboard'.
- Paste the link in a new tab.
  - Are you taken to the video page?
  - If you click play on the video, does it start playing from the specified start time?
  - If you reach the end time, does the clip stop playing?
- Repeat the above process without setting an end time.
  - Does clicking on the link allow you to play the video from the start time to the true end of the video?
- Repeat the above process without setting a start time.
  - Does clicking on the link play the video from the true beginning, until the specified end time?
- Repeat the above process but set a start time after the end time.
  - Do you receive an error informing you that this is invalid?
  - Are you unable to generate a shareable link as a result?
  
