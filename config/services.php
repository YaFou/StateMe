<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Twig\AssetsTwigExtension;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', '../src/**')
        ->exclude(
            [
                '../src/Kernel.php',
                '../src/Domain/**/Entity'
            ]
        );

    $services->load('App\Application\Controller\\', '../src/Application/Controller/**')
        ->tag('controller.service_arguments');

    $services->get(AssetsTwigExtension::class)
        ->args(
            [
                '$environment' => param('kernel.environment'),
                '$manifestPath' => '%kernel.project_dir%/public/assets/manifest.json'
            ]
        );
};
