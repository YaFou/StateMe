<?php

namespace App\Domain\Service\Dto;

use App\Domain\Service\Entity\ServiceStatus;

/**
 * @psalm-suppress MissingConstructor
 */
class UpdateServiceUpdateDto
{
    public ServiceStatus $status;
}
