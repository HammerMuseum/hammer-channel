# Hammer Channel Video Archive

[![CircleCI](https://circleci.com/gh/HammerMuseum/hammer-video/tree/develop.svg?style=svg&circle-token=cb38c33f1816b91c8cbc3a79ff2c75ebb36e9a8f)](https://circleci.com/gh/HammerMuseum/hammer-video/tree/develop)

This application provides the frontend of the Hammer Channel.

To use it with real data, you should either set up the [Hammer Datastore](https://github.com/hammermuseum/hammer-datastore) locally, or, once you have run through the setup below, use the remote Hammer Datastore development environment.

## Requirements

- DDEV
- NodeJS 14 (use [nvm](https://github.com/nvm-sh/nvm/blob/master/README.md#intro))

## Getting started

```sh
cp .env.ddev.example .env
ddev start
```

### Connect to a remote datastore

By default this application is setup to connect to a local copy of the [Hammer Datastore](https://github.com/hammermuseum/hammer-datastore). To connect to the staging Datastore service, update the value of:

`DATASTORE_URL` and `MIX_DATASTORE_URL` to be: <https://stage.datastore.hammer.cogapp.com/api>

### Build the frontend application

```sh
# Build frontend and watch for changes.
npm run watch

# Hot reload - currently not working.
npm run hot
```

You should be able to access the site at <https://hammer-channel.ddev.site/>.

### Front-end notes

Most of the time during development you'll likely just want to run `npm run hot`, but a full list of commands is located in the `scripts` section of the `package.json`.

Please don't bypass or disable the linting rules or webpack configuration we have setup yourself. They are there for everyone's benefit (including the user), but if they do become particularly annoying please feel free to suggest changes.

Stylelint is currently enforcing a rule aimed at reducing nesting and encourages BEM style classes. [If you aren't familiar in writing BEM, you should read the supporting documentation](./docs/BEM.md).
