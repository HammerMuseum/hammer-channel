<!-- Generate a new file using -->
<!-- sed -e "s/\Social sharing/My story/" -e "s/\168787715/156128780/" -e "s/\social-sharing-168787715/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Social sharing

This is a specification for social sharing.

## Pivotal story

[Social sharing](https://www.pivotaltracker.com/story/show/168787715)

## Git branch

[social-sharing-168787715](https://github.com/HammerMuseum/hammer-video/social-sharing-168787715)

## Story description

**As a user, I want to share a video from the Hammer archive on social media, so that I can disseminate and promote the content or start a discussion**

Developer notes:
- Each sharing service will need us to use a particular set of meta tags. 
- Twitter Card meta tags for... Twitter
- Open Graph tags for Facebook and others
- The callbacks for these tags from the external services will be coming into the app directly to the video page URL.
- The tags themselves should therefor be injected into the page head by Laravel using the necessary metadata it has already collected.
- For Twitter "player" cards it looks like we will need an embed type page to enable the iframed video response - this is a must for clips.
- see: https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/player-card
- Links tend to be shortened by twitter et al so we can try this first without shortening the links ourselves.
- There should be default meta tags and an image to use when non video pages are shared.

Make sure this is tested on the portable telephones when sharing to the instant messaging services like "what's up".

---
- Can I share a video page?
- Is there a sharable link?
- Is there a Facebook share button?
- Is there a Twitter share button?
- Is there a Whatsapp share button?
- Is there a Cite button?
- Is there an email share button?
- Do all the share links generate a sensible URL and sharing message?
- Do clips generate a relevant title?

## Implementation
### Metadata
**Twitter cards**

The tags to be populated:

    <meta name="twitter:card" content="" />
    <meta name="twitter:url" content="" />
    <meta name="twitter:title" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:image" content="" />

These will need to be placed in the head of `app.blade.php` and populated by the default controller for the page:
* Return to the template (e.g.):
  
      'metadata' => [
        'title' => 'Hammer Video Archive',
        'url' => $request->getFullPath(),
        'description' => 'The Hammer Museum video archive',
        'image' => $this->getDefaultImage()
      ]

There will need to be appropriate defaults available for when there is no relevant values for the page e.g a specific image or description.

**Twitter video player**

    <meta name="twitter:player" content="" />
    <meta name="twitter:player:width" content="480" />
    <meta name="twitter:player:height" content="480" />
    
The player card needs to be populated with the location of the template containing the video. In our case this is a Vue component so there needs to be some consideration of what actually gets supplied here.

Height & width are provisional.


**Open graph / Facebook**

The tags to be populated:

        <meta property="og:site_name" content=""/>
        <meta property="og:url" content="" />
        <meta property="og:title" content=""/>
        <meta property="og:description" content=""/>
        <meta property="og:image" content="" />
        <meta property="og:image:type" content="image/jpg" />
        <meta property="og:image:width" content="320" />
        <meta property="og:image:height" content="180" />

These will need to be populated in the same way as above.
Height and width values need to be determined.

### Sharing frontend
Install the Vue Social Sharing plugin: https://github.com/nicolasbeauvais/vue-social-sharing

* Implement share buttons in the share panel on the individual video page.
* The social sharing component is implemented like so:

      <social-sharing url="https://vuejs.org/"
          title="Neil DeGrasse Tyson & Bill Nye discuss GMOs and 'Food Evolution'"
          description="In the GMO (genetically modified organisms) debate, both pro and anti camps claim science is on their side."
          quote="The Hammer Museum Video Archive: Neil DeGrasse Tyson & Bill Nye discuss GMOs and 'Food Evolution'"
          hashtags="vuejs,javascript,framework"
          twitter-user="vuejs"
          inline-template>
          
          <div>
            <network network="email">
                <i class="fa fa-envelope"></i> Email
            </network>
            <network network="facebook">
              <i class="fa fa-facebook"></i> Facebook
            </network>
          </div>

## Documentation required
