<!-- Generate a new file using -->
<!-- sed -e "s/\Video page: breadcrumb for 'back to all videos'/My story/" -e "s/\170334139/156128780/" -e "s/\breadcrumb-170334139/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video page: breadcrumb for 'back to all videos'

A specification for a breadcrumb linking back to All Videos.

## Pivotal story

[Video page: breadcrumb for 'back to all videos'](https://www.pivotaltracker.com/story/show/170334139)

## Git branch

[breadcrumb-170334139](https://github.com/HammerMuseum/hammer-video/breadcrumb-170334139)

## Story description
**When arriving on a video page is there a link to take me back to the All Videos page?**

## Implementation
- Edit the individual video template `video.blade.php`
- Add a link to `/`
- Simple styling