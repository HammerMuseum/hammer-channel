<!-- Generate a new file using -->
<!-- sed -e "s/\Footer overlay/My story/" -e "s/\171252808/156128780/" -e "s/\footer-overlay-171252808/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Footer styling

## Related documentation

## Pivotal Story

* [Footer styling](https://www.pivotaltracker.com/story/show/172075980)

## Description
**As a user, I want to gain extra information about the video archive so that I can verify, reference or validate what I'm seeing**

## Requirements to test
- Access to the testing environment

## Test Plan
- Navigate to the homepage
  - Is there a button in the bottom-left?
- Click on the button in the bottom-left.
  - Does a footer overlay open at the bottom of the screen?
  - Does the overlay contain external links to (should open in new window)
    - Terms & Conditions
    - Privacy policy
    - Email sign up
    - Upcoming programs at Hammer
  - Does clicking outside the footer hide it?
  - Does clicking the close icon hide the footer?
  - Does the footer appear and hide correctly on the front page?
  - Does the footer appear and hide correctly on the Search page?
  - Does the footer appear and hide correctly on the Videp page?
