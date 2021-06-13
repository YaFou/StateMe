<?php

namespace Symfony\Config;

return static function (DamaDoctrineTestConfig $config) {
    $config->enableStaticConnection()
        ->enableStaticMetaDataCache(true)
        ->enableStaticQueryCache(true);
};
