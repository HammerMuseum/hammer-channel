<!-- Generate a new file using -->
<!-- sed -e "s/\Email signup/My story/" -e "s/\171252821/156128780/" -e "s/\email-signup-171252821/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Email signup

This is a specification for email signups.

## Pivotal story

[Email signup](https://www.pivotaltracker.com/story/show/171252821)

## Git branch

[email-signup-171252821](https://github.com/HammerMuseum/hammer-video/email-signup-171252821)

## Story description
**As a user, I want to sign up for the Hammer Video archive mailing list so that I can be informed about new additions to the archive**

- Will need to integrate with Mailchimp. Create an API endpoint and use a Laravel wrapper around the Mailchimp api to do this easy task.

---
- Is there a field to enter an email address?
- Is there validation?
- Is there information about how my email is used?


## Implementation
- Install the [Laravel newsletter module](https://github.com/spatie/laravel-newsletter)
- Add Mailchimp API key and list information to `newsletter.php` config.
  - Can use placeholder from a test account until necessary.
- Add the form elements to the existing placeholder tab in the footer. 
  - This should be a Vue component that submits via AJAX
- Add a form submit controller e.g `NewsletterController.php`
- Import the `Newsletter` class from the module to the controller.
- Validate form submission.
- Subscribe the user to the mailing list.
- Return success/failure as JSON with a message.
- Represent the JSON message on the page.
- Styling.
- Add the Mailchimp config variables to provisioning .env file

## Documentation required
- Configuration
