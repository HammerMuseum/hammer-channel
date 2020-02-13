<!-- Generate a new file using -->
<!-- sed -e "s/\k/My story/" -e "s/\170828268/156128780/" -e "s/\homepage-topic-list-170828268/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Topic/List homepage component

This is a template for a specification.

## Pivotal story

[Topic/List homepage component](https://www.pivotaltracker.com/story/show/170828268)

## Git branch

[homepage-topic-list-170828268](https://github.com/HammerMuseum/hammer-video/homepage-topic-list-170828268)

## Story description

## Implementation

- The `topics` field in Elasticsearch index should be converted to a split field via the harvester as sometimes there are multiple topics.
- The field should also be made into a 'keyword' field.
- To get a list of all topics in the backend, perform a search with the parameter `_source=topics`.
  - This could take the form of a re-usable method that gets all values from a specified field.
- Use the list of topics on the frontend by using the `term` endpoint to retrieve all videos for each topic.
- Initially fetch all videos, un-limited.


    {
        "size": "0",
        "aggs": {
            "topics": {
                "terms": {
                    "field": "topics.keyword",
                    "size": 12
                },
                "aggs": {
                    "by_top_hit": {
                        "top_hits": {
                            "sort": [
                                {
                                    "date_recorded": {
                                        "order": "desc"
                                    }
                                }
                            ],
                            "size": 1,
                            "_source": ["title", "thumbnail_url", "title_slug"]
                        }
                    }
                }
            }
        }
    }


- Implement Vue slick carousel on the video thumbnails to build a row.
  - This should be a new Vue component.
- Add a title to each row.
- Each row needs to be anchored.
- List out the topics as links that can be used to navigate to each corresponding row.
- Add a 'See all' card to the end of the carousel which links to a search results page filtered by the topic.


## Documentation required
- Slick carousel usage
