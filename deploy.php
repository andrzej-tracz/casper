<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'casper');

// Project repository
set('repository', 'git@polcode.githost.io:atracz/casper.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Relative paths to remove from server
add('clear_paths', [
    '.git',
    '_docker',
    'tests',
    '.babelrc',
    '.editorconfig',
    '.env.example',
    '.gitattributes',
    '.gitignore',
    '.gitlab-ci.yaml',
    'docker-compose.yaml',
    'Makefile',
    'phpunit.xml',
    'server.php',
    'webpack.mix.js',
    'changelog.md',
    'readme.md',
    'deploy.php',
]);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts
host('atracz.saturn.polcode.com')
    ->port(50022)
    ->set('keep_releases', 1)
    ->stage('dev')
    ->set('branch', 'master')
    ->set('deploy_path', '~/projects/casper/deployer');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

desc('Execute artisan optimize');
task('artisan:optimize', function () {
    run('{{bin/php}} {{release_path}}/artisan optimize');
});

desc('Build project assets');
task('deploy:assets', function () {
    runLocally('npm install');
    runLocally('npm run prod');
    upload('public', '{{release_path}}');
});

/**
 * Main task
 */
desc('Deploy your project');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:assets',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:clear',
    'artisan:cache:clear',
    'artisan:config:cache',
    'artisan:optimize',
    'deploy:clear_paths',
    'artisan:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');
