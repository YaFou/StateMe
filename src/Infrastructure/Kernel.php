<?php

namespace App\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../../config/{packages}/*.php');
        /** @psalm-suppress MixedArgument */
        $container->import(sprintf('../../config/{packages}/%s/*.php', $this->environment));

        $container->import('../../config/{services}.php');
        /** @psalm-suppress MixedArgument */
        $container->import(sprintf('../../config/{services}_%s.php', $this->environment));
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        /** @psalm-suppress MixedArgument */
        $routes->import(sprintf("../../config/{routes}/%s/*.php", $this->environment));
        $routes->import('../../config/{routes}/*.php');
    }
}
