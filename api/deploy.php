<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'element');

// Project repository
set('repository', 'https://github.com/dzantiev/element.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
set('shared_files', [
 'app/config/config.php',
 'public/.htaccess'
]);
set('shared_dirs', []);

// Writable dirs by web server
set('writable_dirs', []);

set('default_stage', 'production');

// Hosts

host('82.202.204.145')
    ->user('www-data')
    ->port('22')
    ->stage('production')
    ->set('deploy_path', '/var/www/element.boi');


// Tasks

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
