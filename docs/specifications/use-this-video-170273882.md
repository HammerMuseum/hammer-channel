<!-- Generate a new file using -->
<!-- sed -e "s/\Video page: use this video panel/My story/" -e "s/\170273882/156128780/" -e "s/\use-this-video-170273882/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video page: use this video panel

This is a specification for the 'Use this Video' option on the individual video page.

## Pivotal story

[Video page: use this video panel](https://www.pivotaltracker.com/story/show/170273882)

## Git branch

[use-this-video-170273882](https://github.com/HammerMuseum/hammer-video/use-this-video-170273882)

## Story description
**As a user, I want to request a video so I can use it for academic or commercial purposes**

Developer notes:
- This is just an email address 

---
- Is there a 'Use this video' button on the video page?
- Is there an email address or link to a form?
- Does the panel slide out and the video player respond accordingly?

## Implementation
- The email address to send this request to should live in the `.env` file.
- Create a 'UseComponent' - when opened it displays a few lines of text about usage and a form.
- The form should have fields for name, email and a note about the request.
- The form should submit, validate, then send to the email address.
- A controller for handling the form submit e.g `RequestController`
- Should return a confirmation of success/failure.

## Documentation required
