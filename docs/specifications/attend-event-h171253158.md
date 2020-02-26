<!-- Generate a new file using -->
<!-- sed -e "s/\Attend next event drawer/My story/" -e "s/\171253158/156128780/" -e "s/\attend-event-h171253158/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Attend next event drawer

This is a template for a specification.

## Pivotal story

[Attend next event drawer](https://www.pivotaltracker.com/story/show/171253158)

## Git branch

[attend-event-h171253158](https://github.com/HammerMuseum/hammer-video/attend-event-h171253158)

## Story description

**As a user, I want to see what's on at the Hammer, so that I can attend a public program in person**

Add a link to the Programs & Events page in the third drawer on the video page.

---
- Is there an upcoming programs drawer?
- Does it show a link to the Programs & Events page on the main Hammer website?

## Implementation
**The drawer functionality**
* A drawer component with a drawer container
  * Should be positioned absolutely with `bottom: 0`
* Three drawers inside the container
* Show/hide the drawers by toggling a class
* Style the drawers as though they are open
* Transform the drawers with `translateY(100% - [height of title])` 
* The active/open class should remove the transform
* The drawer relevant to this story should contain a link to the Hammer "Programs & Events" page.
* Some basic / responsive styling


## Documentation required
