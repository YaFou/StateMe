<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

trait FixturesTrait
{
    public function loadFixture(string $name): array
    {
        $databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();

        return $databaseTool->loadAliceFixture(
            [
                __DIR__ .
                DIRECTORY_SEPARATOR .
                'fixtures' .
                DIRECTORY_SEPARATOR .
                str_replace('/', DIRECTORY_SEPARATOR, $name) .
                '.yaml'
            ]
        );
    }
}
