<!-- Generate a new file using -->
<!-- sed -e "s/\Email signup/My story/" -e "s/\171252821/156128780/" -e "s/\email-signup-171252821/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Email signup

## Related documentation

## Pivotal Story

* [Email signup](https://www.pivotaltracker.com/story/show/171252821)

## Git branch

* [email-signup-171252821](https://github.com/HammerMuseum/hammer-video/tree/email-signup-171252821)

## Description
**As a user, I want to sign up for the Hammer Video archive mailing list so that I can be informed about new additions to the archive**

## Requirements to test
Access to the testing environment.

## Test Plan
- Go to the homepage
- Click on the icon in the bottom left of the page.
  - Does the footer open?
  - Is there a tab labelled 'Email sign up'?
- Click on the 'Email sign up' tab
  - Is there a form containing:
    - Email address input
    - Submit button?
  - Is there some accompanying text?
- Enter an email address into the form and click submit.
  - Is a message returned indicating success?
- Try to submit the form with no email address.
  - Are you prevented from doing so?
- Try to submit the form with an invalid email address e.g 'some word' or 'email@nothing'
  - Are you prevented from doing so?
