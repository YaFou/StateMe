<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true)
        ->load('App\\', '../src/')
        ->exclude(
            [
                '../src/Kernel.php',
                '../src/Domain/**/Entity'
            ]
        );
};
