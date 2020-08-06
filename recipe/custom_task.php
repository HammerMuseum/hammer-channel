<?php

namespace Deployer;

desc('Upload an extra file');
task('custom:copy', function () {
    upload('public/file.example', '{{release_path}}/public', []);
});
