# Hammer Channel front-end application

[![CircleCI](https://circleci.com/gh/HammerMuseum/hammer-video/tree/develop.svg?style=svg&circle-token=cb38c33f1816b91c8cbc3a79ff2c75ebb36e9a8f)](https://circleci.com/gh/HammerMuseum/hammer-video/tree/develop)

This application provides the front-end of the Hammer Channel.

The data for the front-end comes from the [datastore](https://github.com/hammermuseum/hammer-datastore).
You will also need to set this up to get access to data in the development environment.

## Requirements

- DDEV
- NodeJS 14 (use [nvm](https://github.com/nvm-sh/nvm/blob/master/README.md#intro))

## Getting started

### Create the DDEV environment

Create a local copy of the `.env` file by copying the ddev example file.

```sh
cp .env.ddev.example .env
ddev start
```

The site should be running at <https://hammer-channel.ddev.site>.

If you get a message about a missing application key, run the following command:

```sh
ddev exec php artisan key:generate && php artisan config:clear && php artisan config:cache
```

### Connecting to a remote datastore

By default this application will send queries for data to the local
DDEV [backend](https://github.com/hammermuseum/hammer-datastore).

If you don't want to set this up or want to change the environment that
the front-end is connecting to, change the following values in the `.env` file.

```env
DATASTORE_URL=
MIX_DATASTORE_URL=
```

### Build the front-end application

```sh
# Build front-end and watch for changes.
npm run dev

# Laravel Mix hot reload - currently not working with ddev.
npm run hot
```

### Access the application

The application will be running at <https://hammer-channel.ddev.site/>.

## Dev tools

### phpstan

You can use phpstan to find potential issues with PHP code.

`vendor/bin/phpstan analyse`

The config file is `phpstan.neon`.

## Front-end notes

Most of the time during development you'll likely just want to run `npm run hot`, but
a full list of commands is located in the `scripts` section of the `package.json`.

Please don't bypass or disable the linting rules or webpack configuration we have setup
yourself. They are there for everyone's benefit (including the user), but if they do
become particularly annoying please feel free to suggest changes.

Stylelint is currently enforcing a rule aimed at reducing nesting and encourages BEM
style classes. [If you aren't familiar in writing BEM, you should read the
supporting documentation](./docs/BEM.md).
