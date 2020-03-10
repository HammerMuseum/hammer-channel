<!-- Generate a new file using -->
<!-- sed -e "s/\Search result count/My story/" -e "s/\/156128780/" -e "s/\quote-field-171693068/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Quote field

This is a specification for pull quotes.

## Pivotal story

[Quote field](https://www.pivotaltracker.com/story/show/171693068)

## Git branch

[quote-field-171693068](https://github.com/HammerMuseum/hammer-video/quote-field-171693068)

## Story description
**As a user, I want to see a pull quote layered over featured videos so that I can have more context when choosing a relevant video from the selection.**

**Developer notes**
- A field needs to be added to Asset Bank
- The field needs to be pulled into Elasticsearch
- The field needs to display on the frontend

---
**Acceptance criteria**
- Is there a relevant quote displayed by videos in the featured playlist?
- Can I update the quote in Asset Bank?
- If there is no pull quote associated with a video, can I see the title of the video in its place?

## Implementation
- Create a new text field in Asset Bank for 'quote'
- Update Elasticsearch mapping to include a new quote field of type keyword
- Update harvester to pull quote field through
- Update search API to return pull quote in correct places.
- Display quote on frontend with the featured videos.
- Do a check so that if there is no pull quote, the title displays instead.
