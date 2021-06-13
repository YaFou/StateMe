<?php

namespace Symfony\Config;

return static function (MonologConfig $config) {
    $config->handler('main')
        ->type('fingers_crossed')
        ->actionLevel('error')
        ->handler('nested')
        ->bufferSize(50)
        ->excludedHttpCode()
        ->code(404)
        ->code(405);

    $config->handler('nested')
        ->type('stream')
        ->path('php://stderr')
        ->level('debug')
        ->formatter('monolog.formatter.json');

    $config->handler('console')
        ->type('console')
        ->processPsr3Messages(false)
        ->channels()
        ->elements(['!event', '!doctrine']);
};
