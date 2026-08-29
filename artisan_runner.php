<?php
chdir('C:/Users/user/shule');
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput(array_merge(['artisan'], array_slice($argv, 1))),
    new Symfony\Component\Console\Output\ConsoleOutput
);
exit($status);
