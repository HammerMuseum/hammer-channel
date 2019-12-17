<!-- Generate a new file using -->
<!-- sed -e "s/\Initial page listing/My story/" -e "s/\169994869/156128780/" -e "s/\listing-page-169994869/`git_current_branch`/g" spec-template.md | tee "`git_current_branch`.md" -->

# Initial page listing

Initial page listing showing all videos, using only the API.

## Pivotal story

[Initial page listing](https://www.pivotaltracker.com/story/show/169994869)

## Git branch

[listing-page-169994869](https://github.com/HammerMuseum/hammer-video/listing-page-169994869)

## Story description

**Output all the videos in a grid-layout list onto a webpage on a shareable environment**

Links should be a thumbnail of the video with the title displayed in the vicinity.

This page should be driven by Elasticsearch, via a request for all videos. It can then be enhanced as necessary with search form and other features in future.

---
- Is there a link to the page in this story?
- Are there multiple thumbnails on the page?
- Are all the videos in the DAMS presented on the page?
- Are the titles of the videos included? 
- Is there a link to take me through to each video page?

## Implementation
- Create a listing template
- Create a `result-grid` partial template to be re-used
- Include the partial in the listing template
- Create a listing controller to call the API and return appropriate data to view.
- Add to web routes (as the homepage at `/`).
- Pass variables from view into the partial.
- Update partial to receive data in a re-usable format.
- Style in a grid layout.
- Link each thumbnail to individual video pages.

## Documentation required
n/a