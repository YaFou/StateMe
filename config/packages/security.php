<?php

namespace Symfony\Config;

return static function (SecurityConfig $config) {
    $config->enableAuthenticatorManager(true);

    $config->provider('users_in_memory')
        ->memory();

    $config->firewall('dev')
        ->pattern('^/(_(profiler|wdt)|css|images|js)/')
        ->security(false);

    $config->firewall('main')
        ->lazy(true)
        ->provider('users_in_memory');
};
