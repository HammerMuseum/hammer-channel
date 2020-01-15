<!-- Generate a new file using -->
<!-- sed -e "s/\Listings page layouts/My story/" -e "s/\168787613/156128780/" -e "s/\listings-page-layouts-168787613/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Listings page layouts

## Pivotal story

[Listings page layouts](https://www.pivotaltracker.com/story/show/168787613)

## Git branch

[listings-page-layouts-168787613](https://github.com/HammerMuseum/hammer-video/listings-page-layouts-168787613)

## Story description

**Acceptance criteria**
- On a listings page, are the videos displayed in a grid by default on desktop and listing on mobile?

## Implementation

- Modify the current styles to deliver a stacked column layout for mobile, changing to
a three across grid at 750px wide.

- Make necessary changes to the app.pcss and blade tempaltes to accomplish the above.

## Documentation required

None
