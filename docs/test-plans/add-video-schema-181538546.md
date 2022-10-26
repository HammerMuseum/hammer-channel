# Frontend: Add video schema data to each page

## Related documentation

## Pivotal Story

* [Frontend: Add video schema data to each page](https://www.pivotaltracker.com/story/show/181538546)

## Git branch

* [add-video-schema-181538546](https://github.com/HammerMuseum/hammer-video/tree/add-video-schema-181538546)

## Description
https://developers.google.com/search/docs/advanced/structured-data/video

e.g. for https://channel.hammer.ucla.edu/video/1580/laverne-cox-yance-ford-sam-feder-amy-scholder-discuss-the-film-disclosure

This should be in a `<script type="application/ld+json">` tag in the `<head>`

```
{
  "@context": "https://schema.org",
  "@type": "VideoObject",
  "name": "Laverne Cox, Yance Ford, Sam Feder & Amy Scholder discuss the film \"Disclosure\"",
  "description": "Disclosure (2020) producer and director Yance Ford moderates a Q&A with the film's director Sam Feder, producer Amy Scholder, and Emmy Award winner Laverne Cox. An unprecedented, eye-opening look at transgender depictions in film and TV, Disclosure reveals how Hollywood simultaneously reflects and manufactures deep anxieties about gender. Leading trans thinkers and creatives, including Laverne Cox, Lilly Wachowski, Yance Ford, Mj Rodriguez,and Jamie Clayton, share their reactions to some of Hollywood's most beloved moments.",
  "thumbnailUrl": [
    "https://channel.hammer.ucla.edu/images/d/large/22137999ed660b58ba.jpg"
  ],
  "uploadDate": "2021-01-19T13:25:00.000Z",
  "duration": "PT00H34M30S",
  "embedUrl": "https://channel.hammer.ucla.edu/container/1728"
}

```

## Requirements to test
Access to the dev site https://dev.video.hammer.cogapp.com/

## Test Plan
- Goto Hammer channel and click on any video, now open dev tools and select the <html> tag and right click and choose edit as html -> select all and copy.
- Now go to https://search.google.com/test/rich-results and choose the code panel and paste the copied html from the page.
- Now press 'test' button and wait for results, you should get '1 valid item detected'.
- Now go to dev tools and find the <script type="application/ld+json"></script> tag.
  - Validate the duration stamp in json matches the duration of actual video
  - Ensure Json is correct according to video data, for e.g. title, description and other properties matches
- Now try 5 more random videos as above and all should have 1 valid item.