<?php

namespace Deployer;

$depRoot = 'deployer/';

// 設定読み込み
inventory($depRoot.'hosts.yml');

require 'recipe/common.php';
require 'recipe/rsync.php';
require 'recipe/laravel.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@domain.com:username/repository.git');

// Shared files/dirs between deploys
//add('shared_files', []);
//add('shared_dirs', [
//]);

// Writable dirs by web server
//add('writable_dirs', []);

// Number of releases to keep.
set('keep_releases', 10);

// composer with --no-dev
set('composer_options', 'install --no-dev');

// {{{ ローカルでgit cloneし、tarで固めてサーバでuntarする

require 'recipe/local_git.php';

set('date', date('Ymd'));

//set('local_work_dir', 'work');
set('local_work_dir', $depRoot.'work');
set('git_clone_dir', function () {
    return get('local_work_dir').'/'.get('application');
});
set('tar_dir', $depRoot.'release');
set('tar_file_name', function () {
    return get('application').'_'.get('date').'.tar.gz';
});

$excludes = [
    '.git',
    '.gitignore',
    '.editorconfig',
    '.gitattributes',
    '.styleci.yml',
    'README.md',
    '_docs',
    'deployer',
    'deployer.php',
];

set('exclude_archive', $excludes);

// }}}
// {{{ chown

require 'recipe/chown.php';

set('chown_dirs', [
    '{{release_path}}'
]);
set('http_user', 'apache');
set('http_group', 'apache');
set('chown_use_sudo', true);
set('chown_recursive', true);
set('chown_tty', true);

// }}}
// {{{ rsync

set('rsync', [
    'include'       => [],
    'include-file'  => false,
    'exclude'       => $excludes,
    'exclude-file'  => false,
    'filter'        => [],
    'filter-file'   => false,
    'filter-perdir' => false,
    'flags'         => 'rz',
    'options'       => ['delete'],
    'timeout'       => 300,
]);
set('rsync_src', get('git_clone_dir'));

task('clone-rsync', [
    'local-git:clone',
    'rsync'
]);

// }}}

// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('local-git:failed', 'deploy:unlock');

// Migrate database before symlink new release.
//before('deploy:symlink', 'artisan:migrate');

/**
 * Main task
 */
desc('Deploy plus_laravel');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    //'clone-rsync',
    'local-git:tar_upload',
    //'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:optimize',
    'deploy:owner',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');
