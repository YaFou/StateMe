<?php

namespace Symfony\Config;

return static function (MonologConfig $config) {
    $config->handler('main')
        ->type('stream')
        ->path("%kernel.logs_dir%/%kernel.environment%.log")
        ->level('debug')
        ->channels()
        ->elements(['!event']);

    $config->handler('console')
        ->type('console')
        ->processPsr3Messages(false)
        ->channels()
        ->elements(['!event', '!doctrine', '!console']);
};
