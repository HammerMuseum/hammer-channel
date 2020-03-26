<!-- Generate a new file using -->
<!-- sed -e "s/\Topics and tags filter/My story/" -e "s/\171834504/156128780/" -e "s/\topics-tags-filter-171834504/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Topics and tags filter

## Related documentation

## Pivotal Story

* [Topics and tags filter](https://www.pivotaltracker.com/story/show/171834504)

## Git branch

* [topics-tags-filter-171834504](https://github.com/HammerMuseum/hammer-video/tree/topics-tags-filter-171834504)

## Description

**As a user who has conducted a search, I want to be able to filter my results down by topic and tag so I can see how broadly my search term is relevant in the archive and/or more specifically drill down into the topic or tag I am interested in**

## Requirements to test
Access to the testing environment.

## Test Plan
- Navigate to the search page `/search`
  - On the left-hand side, do you see a facet 'Topics & Tags'?
- Click on the Topics & Tags facet
  - Does a box open to the right?
  - Is there a visible search bar?
  - Beneath the search bar, is there a list of topics?
  - Beneath the list of topics, is there a list of tags?
  - Is the box scrollable?
- Type a letter in the search bar.
  - Do both lists filter down based on the letter you typed?
- Continue typing a word
  - Do both lists continue to filter down as you type?
  - If your search yielded no results, is that made clear by a message?
- Click on any topic or tag.
  - Does the box close?
  - Are the search results now filtered by the topic or tag you selected?
