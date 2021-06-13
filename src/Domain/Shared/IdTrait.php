<?php

namespace App\Domain\Shared;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait IdTrait
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[
        ORM\Column(type: 'uuid'),
        ORM\Id,
        ORM\GeneratedValue(strategy: 'UUID')
    ]
    private UUID $id;

    public function getId(): Uuid
    {
        return $this->id;
    }
}
