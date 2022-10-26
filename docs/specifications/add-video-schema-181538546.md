# Frontend: Add video schema data to each page

## Pivotal story

[Frontend: Add video schema data to each page](https://www.pivotaltracker.com/story/show/181538546)

## Git branch

[add-video-schema-181538546](https://github.com/HammerMuseum/hammer-video/add-video-schema-181538546)

## Story description
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

## Implementation
- Use the schema data from the existing method 'view@VideoController' in the front-end hammer-channel repo and create a VideoObject as following.
  - Use 'title' as 'name'
  - Use description as description
  - Use 'thumbnail_url' as 'thumbnailUrl'
  - Use 'duration' as 'duration', match the time format as in below example
  - Use '$state["video"]["id"]' from data response and build a URL, e.g. 'https://channel.hammer.ucla.edu/container/$state["video"]["id"]' and put in value of 'contentUrl' in the VideoObject
  - Use date_recorded as the 'uploadDate', match the time format as in below example
  - An example videoObject is below, hard code @context and @type values at the top.
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
  "contentUrl": "https://channel.hammer.ucla.edu/container/1728"
}

```
- In the 'app.blade.php' include the VideoObject under the '<header><script></script></header>' tags.

## Documentation required
No