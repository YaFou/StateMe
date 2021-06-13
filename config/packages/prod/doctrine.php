<?php

namespace Symfony\Config;

return static function (DoctrineConfig $config, FrameworkConfig $frameworkConfig) {
    $entityManager = $config->orm()
        ->autoGenerateProxyClasses(false)
        ->entityManager('default');

    $entityManager->metadataCacheDriver()
        ->type('pool')
        ->pool('doctrine.system_cache_pool');

    $entityManager->queryCacheDriver()
        ->type('pool')
        ->pool('doctrine.system_cache_pool');

    $entityManager->resultCacheDriver()
        ->type('pool')
        ->pool('doctrine.system_cache_pool');

    $frameworkConfig->cache()
        ->pool('doctrine.result_cache_pool')
        ->adapters(['cache.app']);

    $frameworkConfig->cache()
        ->pool('doctrine.system_cache_pool')
        ->adapters(['cache.system']);
};
