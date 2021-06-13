<?php

namespace App\Domain\Shared;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[
        ORM\Column(type: 'integer'),
        ORM\Id,
        ORM\GeneratedValue
    ]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
