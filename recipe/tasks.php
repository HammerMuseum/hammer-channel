<?php

namespace Deployer;

desc('Change app:permissions');
task('app:permissions', function () {
    run('cd {{release_path}} && sudo chown -R {{app_user}}:{{app_user}} storage bootstrap/cache');
    run('cd {{release_path}} && sudo chown -R {{http_user}}:{{http_user}} public');
});

desc('Execute artisan responsecache:clear');
task('artisan:responsecache:clear', artisan('responsecache:clear'));
