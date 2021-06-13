<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Domain\Incident\Repository\IncidentStatusRepository;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true)
        ->set(IncidentStatusRepository::class)
        ->public();
};
