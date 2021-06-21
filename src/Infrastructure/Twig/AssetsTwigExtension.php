<?php

namespace App\Infrastructure\Twig;

use Psr\Cache\CacheItemPoolInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetsTwigExtension extends AbstractExtension
{
    private const DEVELOPMENT_URL = 'http://localhost:3000/%s';

    private bool $inDevelopment;

    public function __construct(
        string $environment,
        private CacheItemPoolInterface $cache,
        private string $manifestPath
    ) {
        $this->inDevelopment = 'dev' === $environment;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [new TwigFunction('assets', [$this, 'assets'], ['is_safe' => ['html']])];
    }

    public function assets(string $assetsName): string
    {
        return $this->inDevelopment ?
            $this->generateAssetCodeInDevelopment($assetsName) :
            $this->generateAssetCodeInProduction($assetsName);
    }

    private function generateAssetCodeInDevelopment(string $assetsName): string
    {
        return sprintf('<script defer src="%s"></script>', sprintf(self::DEVELOPMENT_URL, $assetsName));
    }

    private function generateAssetCodeInProduction(string $assetsName): string
    {
        $codeItem = $this->cache->getItem(sprintf('assets:%s', $assetsName));

        if ($codeItem->isHit()) {
            /** @psalm-var string */
            return $codeItem->get();
        }

        $manifestItem = $this->cache->getItem('assets');

        if ($manifestItem->isHit()) {
            /** @psalm-var array<string, array{file: string, css: string[]}> $assets */
            $assets = $manifestItem->get();
        } else {
            /** @psalm-var array<string, array{file: string, css: string[]}> $assets */
            $assets = json_decode(file_get_contents($this->manifestPath), true);
            $this->cache->save($manifestItem->set($assets));
        }

        $entry = $assets[$assetsName];
        $code = sprintf('<script defer src="/assets/%s"></script>', $entry['file']);

        foreach ($entry['css'] ?? [] as $css) {
            $code .= sprintf('<link rel="stylesheet" href="/assets/%s">', $css);
        }

        $this->cache->save($codeItem->set($code));

        return $code;
    }
}
