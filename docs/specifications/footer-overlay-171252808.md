<!-- Generate a new file using -->
<!-- sed -e "s/\Footer overlay/My story/" -e "s/\171252808/156128780/" -e "s/\footer-overlay-171252808/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Footer overlay

This is a template for a specification.

## Pivotal story

[Footer overlay](https://www.pivotaltracker.com/story/show/171252808)

## Git branch

[footer-overlay-171252808](https://github.com/HammerMuseum/hammer-video/footer-overlay-171252808)

## Story description

**As a user, I want to gain extra information about the video archive so that I can verify, reference or validate what I'm seeing**

Email sign up:
- Add a placeholder as the implementation of this easy feature will be handled in another story.

---
- Is there an icon/button to reveal the footer overlay?
- Is there About text? 
- Is there a set of links?
-- Terms and conditions
-- Privacy policy
-- Email sign up
-- Visit the main Hammer site 
- Is there is a Mellon logo?
- Is there contact information for Hammer and Cogapp?


## Implementation
- Create a footer component.
- Set up tabbing behaviour e.g https://codepen.io/tatimblin/pen/oWKdjR?editors=1010
- Will need tabs for:
  - Terms & conditions
  - Privacy policy
  - Email sign up
- Placeholder content
- Add a link to the main Hammer site
- Add a show/hide button with `v-show` action
- Add a close button
- Place the Mellon logo
- Simple styling
- Responsive behaviour

## Documentation required
