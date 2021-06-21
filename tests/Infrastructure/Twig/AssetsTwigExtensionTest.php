<?php

namespace App\Tests\Infrastructure\Twig;

use App\Infrastructure\Twig\AssetsTwigExtension;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class AssetsTwigExtensionTest extends TestCase
{
    public function testInDevelopment(): void
    {
        $cache = $this->createMock(CacheItemPoolInterface::class);

        $extension = new AssetsTwigExtension('dev', $cache, '');
        self::assertSame('<script defer src="http://localhost:3000/asset"></script>', $extension->assets('asset'));
    }

    public function testInProductionAndCacheNotHit(): void
    {
        $manifestItem = $this->createMock(CacheItemInterface::class);
        $manifestItem->method('isHit')->willReturn(false);
        $manifestItem->expects(self::once())
            ->method('set')
            ->with(['asset' => ['file' => 'compiled-asset']])
            ->willReturnSelf();

        $codeItem = $this->createMock(CacheItemInterface::class);
        $codeItem->method('isHit')->willReturn(false);
        $codeItem->expects(self::once())
            ->method('set')
            ->with('<script defer src="/assets/compiled-asset"></script>')
            ->willReturnSelf();

        $cache = $this->createMock(CacheItemPoolInterface::class);
        $cache->expects(self::exactly(2))->method('save')->withConsecutive([$manifestItem], [$codeItem]);
        $cache->method('getItem')
            ->withConsecutive(['assets:asset'], ['assets'])
            ->willReturnOnConsecutiveCalls($codeItem, $manifestItem);

        $extension = new AssetsTwigExtension('prod', $cache, $this->getManifestPath('simple'));
        self::assertSame('<script defer src="/assets/compiled-asset"></script>', $extension->assets('asset'));
    }

    private function getManifestPath(string $name): string
    {
        return sprintf('%s/fixtures/assets/%s.json', dirname(__DIR__, 2), $name);
    }

    public function testInProductionAndCachedCode(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->expects(self::once())->method('get')->willReturn('code');

        $cache = $this->createMock(CacheItemPoolInterface::class);
        $cache->method('getItem')->with('assets:asset')->willReturn($item);

        $extension = new AssetsTwigExtension('prod', $cache, $this->getManifestPath('simple'));
        self::assertSame('code', $extension->assets('asset'));
    }

    public function testInProductionWithCssWithCachedManifest(): void
    {
        $manifestItem = $this->createMock(CacheItemInterface::class);
        $manifestItem->method('isHit')->willReturn(true);
        $manifestItem->expects(self::once())
            ->method('get')
            ->willReturn(
                [
                    'asset' => [
                        'file' => 'compiled-asset',
                        'css' => ['css1', 'css2']
                    ]
                ]
            );

        $codeItem = $this->createMock(CacheItemInterface::class);
        $codeItem->method('isHit')->willReturn(false);
        $codeItem->expects(self::once())
            ->method('set')
            ->with(
                '<script defer src="/assets/compiled-asset"></script>' .
                '<link rel="stylesheet" href="/assets/css1">' .
                '<link rel="stylesheet" href="/assets/css2">'
            )
            ->willReturnSelf();

        $cache = $this->createMock(CacheItemPoolInterface::class);
        $cache->expects(self::once())->method('save')->with($codeItem);
        $cache->method('getItem')
            ->withConsecutive(['assets:asset'], ['assets'])
            ->willReturnOnConsecutiveCalls($codeItem, $manifestItem);

        $extension = new AssetsTwigExtension('prod', $cache, $this->getManifestPath('simple'));

        self::assertSame(
            '<script defer src="/assets/compiled-asset"></script>' .
            '<link rel="stylesheet" href="/assets/css1">' .
            '<link rel="stylesheet" href="/assets/css2">',
            $extension->assets('asset')
        );
    }

    public function testInProductionWithCssAndCacheNotHit(): void
    {
        $manifestItem = $this->createMock(CacheItemInterface::class);
        $manifestItem->method('isHit')->willReturn(false);
        $manifestItem->expects(self::once())
            ->method('set')
            ->with(
                [
                    'asset' => [
                        'file' => 'compiled-asset',
                        'css' => ['css1', 'css2']
                    ]
                ]
            )
            ->willReturnSelf();

        $codeItem = $this->createMock(CacheItemInterface::class);
        $codeItem->method('isHit')->willReturn(false);
        $codeItem->expects(self::once())
            ->method('set')
            ->with(
                '<script defer src="/assets/compiled-asset"></script>' .
                '<link rel="stylesheet" href="/assets/css1">' .
                '<link rel="stylesheet" href="/assets/css2">'
            )
            ->willReturnSelf();

        $cache = $this->createMock(CacheItemPoolInterface::class);
        $cache->expects(self::exactly(2))->method('save')->withConsecutive([$codeItem], [$manifestItem]);
        $cache->method('getItem')
            ->withConsecutive(['assets:asset'], ['assets'])
            ->willReturnOnConsecutiveCalls($codeItem, $manifestItem);

        $extension = new AssetsTwigExtension('prod', $cache, $this->getManifestPath('css'));

        self::assertSame(
            '<script defer src="/assets/compiled-asset"></script>' .
            '<link rel="stylesheet" href="/assets/css1">' .
            '<link rel="stylesheet" href="/assets/css2">',
            $extension->assets('asset')
        );
    }
}
