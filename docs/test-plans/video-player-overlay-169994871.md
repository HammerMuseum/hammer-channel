<!-- Generate a new file using -->
<!-- sed -e "s/\Video player: title overlay/My story/" -e "s/\169994871/156128780/" -e "s/\video-player-overlay-169994871/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video player: title overlay

## Pivotal Story

* [Video player: title overlay](https://www.pivotaltracker.com/story/show/169994871)

## Git branch

* [video-player-overlay-169994871](https://github.com/HammerMuseum/hammer-video/tree/video-player-overlay-169994871)

## Description
The video player should display a title overlay which follows the show/hide behaviour of the rest of the controls.

## Requirements to test
- Access to the testing environment.

## Test Plan
1. From the application homepage, click on a video thumbnail.
    * You should be taken to a page for an individual video, where you should see a video player.
2. On page load, along with the main controls, you should see an overlay at the top centre of the player.
    * The overlay should correctly display the title of the video you are viewing.
3. Hit the play button on the video.
    * The title overlay should disappear.
4. Hit the full-screen button on the player.
    * The title overlay should still be hidden.
5. While in full-screen, hit the pause button.
    * The title overlay should show again. 