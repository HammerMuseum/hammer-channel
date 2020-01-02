<!-- Generate a new file using -->
<!-- sed -e "s/\Search all text fields/My story/" -e "s/\168787598/156128780/" -e "s/\search-all-fields-168787598/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search all text fields

A specification for searching all Elastic Search text fields.

## Pivotal story

[Search all text fields](https://www.pivotaltracker.com/story/show/168787598)

## Git branch

[search-all-fields-168787598](https://github.com/HammerMuseum/hammer-video/search-all-fields-168787598)

## Story description
**As a user I want to search so that I can discover relevant content**

At present we search across only the title field. This should be expanded to all fields containing text. This will vastly improve discovery of content. The fields that can be added are:
- Description
- Transcription (if present)
- Tags type fields

**Developer notes:**
- Add a new custom Elasticsearch mapping to version control
- First step will be to ensure that all the fields that we want to use in search are being transferred via the Harvester into Elasticsearch
- Ensure all the desired text search fields are setup as type 'text' in Elasticsearch
- Amend the settings and mappings as necessary. e.g. changing analysis, synonyms, stop words etc
- May need to adjust boosts to ensure that hits in titles and descriptions are returned with a higher weighting than hits found in transcriptions (although Elasticsearch will already account for this to a certain extent)
- Fields of type "keyword" will need exact matches to return hits. Fields of type "text" will return partial matches etc

Note that the tags field is being created in #168787722

---
**Acceptance criteria**
- Does searching for a phrase or word return relevant results for videos with that term/phrase in the description/title/transcript? 

## Implementation
- Add a new Elasticsearch mapping to version control in the https://github.com/HammerMuseum/hammer-configuration repo:
    - An `elasticsearch` directory.
    - a `mapping.json` file listing the fields in the index
    - the `description`, `transcription` and `title` fields should be of type `text`
    - the `tags` field should be a `keyword` multi-valued field
- Update mappings as required
- *n.b as of writing this, the tags data is not in the system, so will need to be mocked for the purpose of this story*
- Adjust boosts to ensure that title and description are weighted heavier than transcriptions.
- Update the search query in the backend `Search` class to search across all relevant fields.


- As part of this story, update the frontend to output tags. This will involve:
    - Adding tags to the API response (the backend Resource Model, factory class and anywhere else where the structure is declared or used, including automated tests)
    - Adding tags to the template

## Documentation required
- Documentation for field mappings
