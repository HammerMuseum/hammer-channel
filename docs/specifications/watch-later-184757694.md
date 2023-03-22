# 'Watch later' functionality

Add a 'Watch later' feature to Hammer Channel so users can save videos in a custom playlist

## Pivotal story

[Spike: "Watch later" functionality](https://www.pivotaltracker.com/story/show/184757694)

## Git branch

[watch-later-184757694](https://github.com/HammerMuseum/hammer-channel/watch-later-184757694)

## Story description

**As a user, I want to save videos to a personalised playlist so I can view them later**

## Implementation

- Add a "Watch Later" button to the 'Share' tab on the video page. Rename this to 'Share and Save'
- When the user clicks on the button, the video's id will be stored in a cookie. The button's text should change to "Saved to watch later" to indicate that the video has been added to the playlist. Clicking on the button again will remove the video from the playlist
- The playlist will be saved in a cookie using the 'js-cookie' library.
- Create a new route in routes/web.php, '/watch-later'
- Add a link to this route in the header, next to 'About' 
- In the controller, get the value of the cookie and request info for each video Id
- Use as much of the existing Search page functionality to display the 'Watch later' playlist. e.g. https://channel.hammer.ucla.edu/search?in_playlists=400%20Years%20of%20Inequality
