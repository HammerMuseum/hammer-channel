# Video Archive

A Laravel application.

## Getting started

```sh
cp .env.example .env
```

**Docker**

Install the correct version of Docker for your operating system. 

```sh
tee .env.example.docker .env
```
Check that the correct php image is selected in .env (choice depends on host operating system).

```sh
make up
```

```sh
# When running php-based tools and Docker, prefix commands with:
docker-compose exec php <command>

# e.g.
docker-compose exec php composer install

# and
docker-compose exec php php artisan key: generate
```

Note the double `php` in the second command above. The first `php` refers to the name of the Docker service, the second refers to the command to invoke `php` on the command line.

**Not Docker**

Install `PHP 7.2+` on the host machine or use a tool such as [Laravel Valet](https://laravel.com/docs/6.x/valet).

### Build

```sh
# Install dependencies.
composer install

# Generate the Laravel application key.
php artisan key:generate key

# Install frontend dependencies.
npm install

# Build frontend and watch for changes.
npm run watch
```
