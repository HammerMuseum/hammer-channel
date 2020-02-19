<!-- Generate a new file using -->
<!-- sed -e "s/\Clipping tool/My story/" -e "s/\168787712/156128780/" -e "s/\clipping-tool-168787712/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Clipping tool

This is a specification for the Hammer Video Archive clipping tool.

## Pivotal story

[Clipping tool](https://www.pivotaltracker.com/story/show/168787712)

## Git branch

[clipping-tool-168787712](https://github.com/HammerMuseum/hammer-video/clipping-tool-168787712)

## Story description

**As a user, I want to be able clip a Hammer video so that I can share it on social media or share it with a friend/contact**

See #171075624 in particular comment https://www.pivotaltracker.com/story/show/171075624/comments/211536362

The interface control should follow the patten of:
- Within the share panel;
- There are two input boxes, one with the current time rounded to the nearest second when the set time button is pressed. Second input next to end time box which does the same thing.

Add a button with "Share clip" below
Populate a text field with the link.

- Modify the videocomponent/videoplayer component to open the shared clip link correctly.

## Implementation
* Create a new Clipping Tool component.
* In the component add controls for start time and end time, with buttons for setting them.
* Add a button to generate shareable link, an input where the link is output and a button to copy to clipboard.
* The link should be a link to the current page with `start` and `end` parameters, generated from the values of the start and end time input fields.
* To handle the shareable link, get the start and end parameters from the URL and pass them in through the controller.
* In the VideoPlayer component, when the video has been loaded, if there is a start and end time specified, set it using `this.player.currentTime()`
* To achieve this, an end time property will need to be added to the player.
* Once the video is playing, during the `timeupdate` event, check if the end time has been reached, and pause the video if so.

## Documentation required
