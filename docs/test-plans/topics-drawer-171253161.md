<!-- Generate a new file using -->
<!-- sed -e "s/\Topics and tags drawer/My story/" -e "s/\171253161/156128780/" -e "s/\topics-drawer-171253161/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Topics and tags drawer

## Related documentation

## Pivotal Story

* [Topics and tags drawer](https://www.pivotaltracker.com/story/show/171253161)

## Git branch

* [topics-drawer-171253161](https://github.com/HammerMuseum/hammer-video/tree/topics-drawer-171253161)

## Description
**As a user, I want to see tags and topics related to the video I'm watching, so that I can continue my journey through the archive with something that's likely to be relevant for me**

## Requirements to test
- Access to the testing environment.

## Test Plan
- Navigate to the homepage.
- From the homepage, click into any individual video.
  - On the video page, do you see a label at the bottom of the page for 'Topics and Tags'?
- Click the Topics and Tags label
  - Does the drawer slide up?
  - Does the drawer contain a list of topics and a list of tags?
- Log in to [Asset Bank](http://tpm.office.cogapp.com/index.php/pwd/view/649)
- Locate the video that you viewed on the frontend and click inside it.
- Do the topics match the topic list on the frontend?
- Does the list of tags match what's on the frontend, limited by a number?
- Click on the 'Topics and tags' label again.
  - Does the drawer close?
