<!-- Generate a new file using -->
<!-- sed -e "s/\Search all text fields/My story/" -e "s/\168787598/156128780/" -e "s/\search-all-fields-168787598/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Search all text fields

## Related documentation

## Pivotal Story

* [Search all text fields](https://www.pivotaltracker.com/story/show/168787598)

## Git branch

* [search-all-fields-168787598](https://github.com/HammerMuseum/hammer-video/tree/search-all-fields-168787598)

## Description
**As a user I want to search so that I can discover relevant content**

At present we search across only the title field. This should be expanded to all fields containing text. This will vastly improve discovery of content. The fields that can be added are:

* Description
* Transcription (if present)
* Tags type fields

## Requirements to test
* [Access to the dev environment](http://tpm.office.cogapp.com/index.php/pwd/view/665)

## Test Plan
- Navigate to the homepage
    - This should be a listing of 'All Videos'
- Type anything into the search box (e.g. Hammer) and click 'Search'
    - If you aren't returned any results, try a different search term
- Each result thumbnail/title should link to an individual video page
- Click into any of the results individual video pages
    - Confirm that the term you searched for appears in one of the following:
        - Title
        - Description
        - Tag list
- Return to the 'All Videos' page and repeat the process

