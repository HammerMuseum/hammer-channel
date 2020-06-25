<!-- Generate a new file using -->
<!-- sed -e "s/\Video page - enter fullscreen on device rotation/My story/" -e "s/\173103032/156128780/" -e "s/\${branch}/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video page - enter fullscreen on device rotation

## Related documentation

## Pivotal Story

* [Video page - enter fullscreen on device rotation](https://www.pivotaltracker.com/story/show/173103032)

## Test Plan

- Test on mobile phones and tablets only

- Go to dev.video.hammer.cogapp.com/videos/236/van-goghs-life-in-provence

- Rotate the device into landscape mode

- Check that fullscreen mode is enabled

- Exit fullscreen by using the on-screen controls
