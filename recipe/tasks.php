<?php

namespace Deployer;

desc('Change app:permissions');
task('app:permissions', function () {
    writeln('change permissions to {{http_user}} for {{release_path}}/public');
    run('cd {{release_path}} && chown -Rf {{http_user}}:{{http_user}} public/*');

    run('cd {{deploy_path}}/shared && chown -Rf {{app_user}}:{{app_user}} storage');
    run('cd {{release_path}} && chown -Rf {{app_user}}:{{app_user}} storage bootstrap/cache && chown -Rf {{app_user}}:{{app_user}} public/*');
});

desc('Execute artisan responsecache:clear');
task('artisan:responsecache:clear', artisan('responsecache:clear'));
