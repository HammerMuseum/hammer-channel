<!-- Generate a new file using -->
<!-- sed -e "s/\Search within the transcript/My story/" -e "s/\171998304/156128780/" -e "s/\search-within-transcript-171998304/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search within the transcript

## Related documentation

## Pivotal Story

* [Search within the transcript](https://www.pivotaltracker.com/story/show/171998304)

## Git branch

* [search-within-transcript-171998304](https://github.com/HammerMuseum/hammer-video/tree/search-within-transcript-171998304)

## Description

## Requirements to test

Dev access credentials and browser support list

## Test Plan

Notes:
On some devices the video player and tab bar on the video page will not be "sticky".
This is to prevent it filling the viewport. (e.g. Macbook Air 11 inch).
The criteria for stickiness is: while less than 960px wide, the screen should be twice
as tall as wide to be sticky.

*Search transcript*

- Go to any video page
- Click on the transcript tab
- Scroll down the transcript
- Check that the back to top arrow appears
- Click the back to top arrow to check you are taken back to the top
- Click the search icon within the transcript tab
- Check the Search Transcript overlay opens at the top of the page (narrow screens)
or top of the tab section (wide screens).
- Check that focus is given to the input box when the search within bar opens.
- Enter a query to search the transcript in the input box
- Check that you see a results summary under the input box
- Check that if you have 1 hit it shows "1 of 1" type text.
- Check that if you have multiple hits it shows "1 of x"
- Check that you are scrolled to the first hit for the query.
- Check that the hit is highlighted in pink
- Check that if multiple hits, the non-current hits are highlighted in blue
- Check that if you click next you are scrolled to the next hit
- Check that if you click previous you are scrolled to the previous hit
- Check that if you click clear your hits are cleared from the transcript view
- Check that when you click on a paragraph of text a timestamp appears
- Check that clicking the timestamp navigates you to that part of the video

*Search transcript (previous query)*
- Open the search overlay clicking the search icon at the top right of the page
- Enter a query e.g."hillary clinton"
- Click search
- Select one of the videos from the results
- Open the transcript for the video
- Open the search within transcript tool
- Check that the query is offered as a search option
- Check that clicking the button applies the search you made in the main search bar
- Check that results are shown
- Check that closing the search within tool removes it
- Check that opening the search within tool reveals a clean search input
