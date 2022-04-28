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
  "contentUrl": "https://assetbank-us-east-1.s3.amazonaws.com/hammer_b0f882c43c57e94c02d0900798e043bb/c7a/PP202101-04.mp4?response-content-disposition=attachment%3B%20filename%3D%22PP202101-04.mp4%22%3B%20filename%2A%3DUTF-8%27%27PP202101%252D04%252Emp4&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20220201T131134Z&X-Amz-SignedHeaders=host&X-Amz-Expires=900&X-Amz-Credential=AKIATJ7XNAYVIDRUS5GS%2F20220201%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Signature=abe99ce7cbc5f54d2be79742dcde7f28e5e244e9fee575c44f8e2b1d7817afc8"
}

```

## Implementation
- Use the scehma data from the existing method 'view@VideoController' in the front-end hammer-channel repo and create a VideoObject as following.
  - Use 'title' as 'name'
  - Use description as description
  - Use 'thumbnail_url' as 'thumbnailUrl'
  - Use 'duration' as 'duration', convert the format to ''
  - Use 'src' from data response as 'contentUrl'
  - Use date_recorded as the 'uploadDate'
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
  "contentUrl": "https://assetbank-us-east-1.s3.amazonaws.com/hammer_b0f882c43c57e94c02d0900798e043bb/c7a/PP202101-04.mp4?response-content-disposition=attachment%3B%20filename%3D%22PP202101-04.mp4%22%3B%20filename%2A%3DUTF-8%27%27PP202101%252D04%252Emp4&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20220201T131134Z&X-Amz-SignedHeaders=host&X-Amz-Expires=900&X-Amz-Credential=AKIATJ7XNAYVIDRUS5GS%2F20220201%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Signature=abe99ce7cbc5f54d2be79742dcde7f28e5e244e9fee575c44f8e2b1d7817afc8"
}

```
- In 'TheHeader.vue' include the VideoObject under the <header><script></script></header> tags.

## Documentation required
No
