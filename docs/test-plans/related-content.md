<!-- Generate a new file using -->
<!-- sed -e "s/\${title}/My story/" -e "s/\${number}/156128780/" -e "s/\${branch}/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Related content

## Related documentation

## Pivotal Story

* [Related content](https://www.pivotaltracker.com/story/show/171253159)

## Description

**As a user, I want to see other videos similar to the one I'm watching, so that I can continue my journey through the archive with something that's likely to be relevant for me**

- Is there a related videos tab?
- Does it contain a links to related videos?
- Are the related videos in a carousel?
- Do the related videos have people, tags or topics in common with the video I'm watching?
- Does the video I'm watching *not* appear in the list?
- Can I scroll across to see more related videos?
- Does the related video's title appear?
- Does the related video's date appear?

## Requirements to test

Login creds for dev

## Test Plan

- Go to https://dev.video.hammer.cogapp.com/video/246/black-women-in-media-race-and-gender-inequality-in-hollywood

- Is there a "Related" tab visible?

- Click on the "Related" tab.

  - Does the tab open and display a carousel of linked videos?

  - Can you navigate the carousel using the arrow controls?

  - Does each video display:
   - thumbnail
   - title
   - date

  - Can you navigate the list of related videos using the keyboard?

  - Is there a list of tags associated with the video under the carousel?