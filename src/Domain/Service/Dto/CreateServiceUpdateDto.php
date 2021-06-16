<?php

namespace App\Domain\Service\Dto;

use App\Domain\Service\Entity\Service;
use App\Domain\Service\Entity\ServiceStatus;

/**
 * @psalm-suppress MissingConstructor
 */
class CreateServiceUpdateDto
{
    public Service $service;
    public ServiceStatus $status;
}
