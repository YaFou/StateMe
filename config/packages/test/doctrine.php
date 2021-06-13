<?php

namespace Symfony\Config;

return static function (DoctrineConfig $config) {
    $config->dbal()
        ->connection('default')
        ->dbnameSuffix('_test%env(default::TEST_TOKEN)%');
};
