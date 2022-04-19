<?php
namespace Deployer;

require 'contrib/rsync.php';
require 'recipe/laravel.php';

// Project name
set('application', 'Laravel');

// Project repository
set('repository', 'git@github.com:HammerMuseum/hammer-video.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);

set('http_user', 'www-data');

set('rsync', [
  'exclude'      => [
    'deploy.php',
    '.babelrc',
    '.circleci/',
    '.editorconfig',
    '.env.dusk.local.example',
    '.env.dusk.testing',
    '.env.example.docker',
    '.env.example',
    '.env.testing',
    '.eslintrc.js',
    '.git/',
    '.gitattributes',
    '.gitignore',
    '.styleci.yml',
    '.stylelintrc',
    'composer.json',
    'composer.lock',
    'docker-compose.yml',
    'docker-sync.yml',
    'docs',
    'LaravelREADME.md',
    'Makefile',
    'node_modules/',
    'package-lock.json',
    'package.json',
    'patches',
    'phpcs.xml',
    'phpunit.xml',
    'postcss.config.js',
    'README.md',
    'server.php',
    'storage/',
    'tests/',
    'traefik.yml',
    'webpack.mix.js',
  ],
  'exclude-file' => false,
  'include'      => [],
  'include-file' => false,
  'filter'       => [],
  'filter-file'  => false,
  'filter-perdir'=> false,
  'flags'        => 'rz', // Recursive, with compress
  'options'      => ['delete'],
  'timeout'      => 60,
]);

// Hosts

host('dev.video.hammer.cogapp.com')
    ->set('remote_user', 'deploy')
    ->set('deploy_path', '/var/www/dev.video.hammer.cogapp.com')
    ->set('stage', 'dev');

// Tasks

//task('build', function () {
//    run('cd {{release_path}} && build');
//});

task('build', [
  // prepare is a group task which includes info, setup, lock, release, update_code, shared and writable.
  'deploy:prepare',
//  'deploy:vendor',
  'artisan:storage:link',
//  'artisan:responsecache:clear',
  'artisan:view:clear',
  'artisan:cache:clear',
  'artisan:config:cache',
  // publish is a group task which includes symlink, unlock, cleanup and success.
  'deploy:publish',
]);



// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
