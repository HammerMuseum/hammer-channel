# 'Watch later' functionality

## Pivotal story

[Spike: "Watch later" functionality](https://www.pivotaltracker.com/story/show/184757694)

## Git branch

[watch-later-184757694](https://github.com/HammerMuseum/hammer-channel/watch-later-184757694)

## Story description

**As a user, I want to save videos to a personalised playlist so I can view them later**

## Test plan

- Visit the testing environment (URL TBC)
- You should see a new item in the header navigation with the text 'Saved videos', on mobile this should show a clock icon
- Click the link

### Saved videos page: empty state
- The page should list instructions for saving a video. e.g. "You have no saved videos. To save a video to watch later, click the clock item on any video preview, or the 'Save to watch later' button under the 'Share and Save' tab on any video page."

### Video thumbnail save button
- Click the search icon in the header and enter a search term. e.g. 'Daniel'
- On the results page, hover over a video. You should see a Clock icon over the video thumbnail. Hovering over this element will display the tooltip, 'Save to watch later'
- Click the button. You should see a notification appear at the bottom of the screen saying 'Video saved to watch later'

### Saved videos page: single saved video
- Now click 'Saved videos' in the header
- You should see the video you saved listed
- Clicking on the video should take you to the video page

### Video page save button
- Click the search icon in the header and enter a search term. e.g. 'Gary'
- Click the first result
- On the video page, click the tab 'Share and Save'
- Click the 'Save to watch later' button. You should see a notification appear at the bottom of the screen saying 'Video saved to watch later'
- Repeat the previous four steps for a different search term and video

### Saved videos page: multiple saved videos
- Now click 'Saved videos' in the header
- You should see the three videos you saved

### Removing videos from saved videos
- Hover over one of the videos. You should see a Clock icon over the video thumbnail. Hovering over this element will display the tooltip, 'Remove from saved videos'
- Click the button. You should see a notification appear at the screen saying 'Video removed from watch later'. The video should disappear from the page.
- Click one of the remaining videos
- On the video page, click the tab 'Share and Save'
- Click the 'Remove from saved videos' button. You should see a notification appear at the bottom of the screen saying 'Video removed from watch later'
- Click the search icon in the header and enter a search term related to the one remaining saved video
- Hover over the saved videos. You should see a Clock icon over the video thumbnail. Hovering over this element will display the tooltip, 'Remove from saved videos'
- Click the button. You should see a notification appear at the screen saying 'Video removed from watch later'. The video should disappear from the page.
- Click the 'Saved videos' link in the header. The page should now show the initial empty state
