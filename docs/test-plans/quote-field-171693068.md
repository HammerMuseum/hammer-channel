<!-- Generate a new file using -->
<!-- sed -e "s/\Quote field/My story/" -e "s/\171693068/156128780/" -e "s/\quote-field-171693068/`git_current_branch`/g" template.md | tee "`git_current_branch`.md" -->

# Quote field

## Pivotal Story

* [Quote field](https://www.pivotaltracker.com/story/show/171693068)

## Git branch

* [quote-field-171693068](https://github.com/HammerMuseum/hammer-video/tree/quote-field-171693068)

## Description
**As a user, I want to see a pull quote layered over featured videos so that I can have more context when choosing a relevant video from the selection.**

## Requirements to test
Access to the testing environment

## Test Plan
* Go to Asset Bank
* Log in as [hammersupport](http://tpm.office.cogapp.com/index.php/pwd/view/649)
* Add a quote to any video in the "Featured" playlist


* Run the harvester


* Navigate to the frontend homepage.
* Navigate through the featured videos carousel
  * Does the video you added the quote to now display the quote instead of its title?
  

* In Asset Bank, remove the quote you added.
* Run the harvester


* Navigate to the frontend homepage
* Navigate through the featured carousel
  * Has your removed quote been replaced with the video's title
