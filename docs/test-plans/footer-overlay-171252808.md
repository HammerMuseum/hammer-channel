<!-- Generate a new file using -->
<!-- sed -e "s/\Footer overlay/My story/" -e "s/\171252808/156128780/" -e "s/\footer-overlay-171252808/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Footer overlay

## Related documentation

## Pivotal Story

* [Footer overlay](https://www.pivotaltracker.com/story/show/171252808)

## Git branch

* [footer-overlay-171252808](https://github.com/HammerMuseum/hammer-video/tree/footer-overlay-171252808)

## Description
**As a user, I want to gain extra information about the video archive so that I can verify, reference or validate what I'm seeing**

## Requirements to test
- Access to the testing environment

## Test Plan
- Navigate to the homepage
  - Is there a button in the bottom-left?
- Click on the button in the bottom-left.
  - Does a footer overlay open at the bottom of the screen?
    - Does the overlay contain a title 'About'?
    - Does the overlay contain text about the archive?
    - Is there a Mellon Foundation logo in the bottom-right?
  - Does the overlay contain links to:
    - Terms & Conditions
    - Privacy policy
    - Email sign up
    - The main Hammer website?
- Click on 'Terms & Conditions'
  - Does the 'About' content get replaced with Terms & Conditions content?
- Repeat for 'Privacy Policy' and 'Email sign up'.
- Click on 'Visit the main Hammer site'
  - Are you taken to the main site https://hammer.ucla.edu?
