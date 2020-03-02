<!-- Generate a new file using -->
<!-- sed -e "s/\Topics and tags drawer/My story/" -e "s/\171253161/156128780/" -e "s/\topics-drawer-171253161/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Topics and tags drawer

A specification for the topic and tag drawer on individual video pages.

## Pivotal story

[Topics and tags drawer](https://www.pivotaltracker.com/story/show/171253161)

## Git branch

[topics-drawer-171253161](https://github.com/HammerMuseum/hammer-video/topics-drawer-171253161)

## Story description

**As a user, I want to see tags and topics related to the video I'm watching, so that I can continue my journey through the archive with something that's likely to be relevant for me**

Developer notes:
- Display the topics field and the tags field for the current video
- May need a limit as some videos have a large number of tags?

---
- Is there a tags and topics 'drawer'?
- Does it contain links to tags and topics associated with the video I'm viewing?
- If I click a tag or a topic, am I taken to the filtered search result view for that tag or topic?
- Are all the tags and topics associated with the video I'm watching displayed in the drawer?

## Implementation
* Add the new tag/topic drawer component into an existing 'drawer'
* Pass in the topics and tags from the parent's initial page data response
* List out topics
* List out tags, limited to a sensible number (will need experimentation)
* Link each topic/tag to a search results page filtered by it.


## Documentation required
