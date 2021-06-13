<?php

namespace Symfony\Config;

return static function (DoctrineConfig $config) {
    $config->dbal()
        ->connection('default')
        ->url('%env(resolve:DATABASE_URL)%');

    $config->orm()
        ->autoGenerateProxyClasses(true)
        ->entityManager('default')
        ->autoMapping(true)
        ->namingStrategy('doctrine.orm.naming_strategy.underscore_number_aware')
        ->mapping('App')
        ->isBundle(false)
        ->type('attribute')
        ->dir('%kernel.project_dir%/src/Domain')
        ->prefix('App\Domain')
        ->alias('App');
};
