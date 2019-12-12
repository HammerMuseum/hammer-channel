<!-- Generate a new file using -->
<!-- sed -e "s/\Video page: video title/My story/" -e "s/\169994868/156128780/" -e "s/\video-title-169994868/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Video page: video title

## Related documentation
- [Making POST/PUT/DELETE requests to the Datastore API](https://github.com/HammerMuseum/hammer-datastore/blob/video-endpoint-170053908/docs/api/cud.md)
- [Authenticating requests to the Datastore API](https://github.com/HammerMuseum/hammer-datastore/blob/video-endpoint-170053908/docs/api/authentication.md)

## Pivotal Story

* [Video page: video title](https://www.pivotaltracker.com/story/show/169994868)

## Git branch

* [video-page-elements](https://github.com/HammerMuseum/hammer-video/tree/video-page-elements)

## Description

Update the controller and template to output the title of the video being requested.

---

- Is the video's full title displayed on the page?
- Is the title being pulled from the DAMS?
- If I update the video's title in the DAMS, does the title on the video page update to match (either immediately or at an expected point in the future)?
- Is the video's title the H1 for the page?

## Requirements to test
- A local setup of the `hammer-video` repository (follow the [getting started](../../README.md) guide to ensure your setup is complete)
- Access to the DAMS (at present this is an Asset Bank trial account [TPM Details here](http://tpm.office.cogapp.com/index.php/pwd/view/649))
- A method of making POST requests to the datastore API e.g Postman or cURL.


**n.b.** As there is no data pipeline yet, the penultimate acceptance criteria can't be met. See end of test plan for replacement steps for when the data pipeline is in place.

## Test Plan
- Add a video to the datastore by following these steps:
    1. Locate a video in the DAMS
    2. Make a request to the Datastore API (see Datastore API documentation for [POST/PUT/DELETE](https://github.com/HammerMuseum/hammer-datastore/blob/video-endpoint-170053908/docs/api/cud.md) and [authentication](https://github.com/HammerMuseum/hammer-datastore/blob/video-endpoint-170053908/docs/api/authentication.md)) to add your video to it.
    3. Once the video has been added to the Datastore, the response should indicate success with a status code of 201.
- Navigate to your video page on the frontend e.g `https://video.hammer.cogapp.com/video/[YOUR VIDEO'S ASSET ID]`.
- The video's title should display underneath the video in full.
- The title should match exactly what is in the DAMS.
- Make an update to your video in the Datastore via the API to change the title.
- Refresh the video page.
- The title should now reflect your change.
- The video's title should be encased in an `<h1>` and this should be the only `<h1>` on the page.


## Extended test steps
**Only use once the data pipeline is in place**
- Edit the title of your selected video in the DAMS.
- Run the data harvester[?]
- The title should reflect the change.