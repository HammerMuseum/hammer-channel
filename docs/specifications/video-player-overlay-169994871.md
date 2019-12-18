<!-- Generate a new file using -->
<!-- sed -e "s/\Video player: title overlay/My story/" -e "s/\169994871/156128780/" -e "s/\video-player-overlay-169994871/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video player: title overlay

A specification for adding a title overlay to the out of the box VideoJS player.

## Pivotal story

[Video player: title overlay](https://www.pivotaltracker.com/story/show/169994871)

## Git branch

[video-player-overlay-169994871](https://github.com/HammerMuseum/hammer-video/video-player-overlay-169994871)

## Story description

**As a user I want to be able to see the title of the video within the embedded player so that I am clear about what I am watching.**

Developer notes:
There is a useful Codepen provided by videojs for understanding how customisation of the player works https://codepen.io/heff/pen/EarCt
As part of this story, it may be useful to convert the video player into a Vue component.

Acceptance Criteria:
- Is the title overlayed on the video, as per BBC iPlayer? 
- Is there a dark gradient behind the text to ensure it is legible and accessible?
- Does it follow the behaviour of the other video controls?


## Implementation
- Install video JS overlay plugin
- Configure plugin to accept title of video
- Configure plugin behaviour
- Styling

