<!-- Generate a new file using -->
<!-- sed -e "s/\Topic List homepage component/My story/" -e "s/\170828268/156128780/" -e "s/\homepage-topic-list-170828268/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Topic / List homepage component

## Related documentation

## Pivotal Story

* [Topic / List homepage component](https://www.pivotaltracker.com/story/show/170828268)

## Git branch

* [homepage-topic-list-170828268](https://github.com/HammerMuseum/hammer-video/tree/homepage-topic-list-170828268)

## Description

## Requirements to test
- Access to the testing environment

## Test Plan
- Navigate to the homepage
  - Is there a list of topics on the page?
  - Is there a row of video thumbnails for each topic?
  - Is the row of thumbnails a slider/carousel?
- Click on a topic
  - Are you taken to the corresponding row of video thumbnails for the topic you clicked on?
- Click on the arrows on the carousel. 
  - Do these let you cycle through the thumbnails?
  - At the end of the cycle, is there a 'See all' card?
- Click on the 'See all' card.
  - Are you taken to a search results page, filtered by the topic for the row that you navigated from?
