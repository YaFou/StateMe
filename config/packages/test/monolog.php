<?php

namespace Symfony\Config;

return static function (MonologConfig $config) {
    $mainHandler = $config->handler('main')
        ->type('fingers_crossed')
        ->actionLevel('error')
        ->handler('nested');

    $mainHandler->channels()
        ->elements(['!event']);

    $mainHandler->excludedHttpCode()
        ->code(404)
        ->code(405);

    $config->handler('nested')
        ->type('stream')
        ->path("%kernel.logs_dir%/%kernel.environment%.log")
        ->level('debug');
};
