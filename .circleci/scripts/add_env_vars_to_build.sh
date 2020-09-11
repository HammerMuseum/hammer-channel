#!/usr/bin/env bash

# https://circleci.com/docs/2.0/using-shell-scripts/

# Exit script if you try to use an uninitialized variable.
set -o nounset

# Exit script if a statement returns a non-true return value.
set -o errexit

# Use the error status of the first failure, rather than that of the last item in a pipeline.
set -o pipefail

CIRCLE_BRANCH=$1

if [ "$CIRCLE_BRANCH" = "tagged" ]; then
    echo "MIX_DATASTORE_URL=https://dev.datastore.hammer.cogapp.com/api/" > .env
    echo "MIX_APP_URL=https://dev.video.hammer.cogapp.com" >> .env
fi

if [ "$CIRCLE_BRANCH" = "develop" ]; then
    echo "MIX_DATASTORE_URL=https://stage.datastore.hammer.cogapp.com/api/" > .env
    echo "MIX_APP_URL=https://stage.video.hammer.cogapp.com/api/" >> .env
fi

if [ "$CIRCLE_BRANCH" = "master" ]; then
    echo "MIX_DATASTORE_URL=https://datastore.hammer.ucla.edu/api/" > .env
    echo "MIX_APP_URL=https://video.hammer.ucla.edu/api/" >> .env
fi
