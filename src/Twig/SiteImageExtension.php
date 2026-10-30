<?php

namespace App\Twig;

use App\Entity\SiteImage;
use App\Repository\SiteImageRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ResetInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * site_image('hero') : src, srcset, width, height, alt de l'image uploadée,
 * ou null si aucune image (le template garde alors son image statique).
 */
class SiteImageExtension extends AbstractExtension implements ResetInterface
{
    private ?array $siteImages = null;

    public function __construct(
        private SiteImageRepository $repository,
        private RequestStack $requestStack,
        #[Autowire('%kernel.project_dir%/public')] private string $publicDir,
    ) {
    }

    // vide le cache mémoire entre deux requêtes (workers longs, tests)
    public function reset(): void
    {
        $this->siteImages = null;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('site_image', [$this, 'getSiteImage']),
        ];
    }

    public function getSiteImage(string $slot): ?array
    {
        $this->siteImages ??= $this->repository->findAllIndexedBySlot();
        $siteImage = $this->siteImages[$slot] ?? null;

        if (!$siteImage?->getImage()) {
            return null;
        }

        $size = @getimagesize($this->publicDir.SiteImage::PUBLIC_DIR.'/'.$siteImage->getImage());

        if (!$size) {
            return null;
        }

        [$width, $height] = $size;
        $base = preg_replace('/\.webp$/', '', $siteImage->getImage());
        $src = SiteImage::PUBLIC_DIR.'/'.$siteImage->getImage();

        $srcset = [];
        foreach ($siteImage->getWidths() as $variantWidth) {
            $variant = SiteImage::PUBLIC_DIR."/$base-$variantWidth.webp";
            if ($variantWidth < $width && is_file($this->publicDir.$variant)) {
                $srcset[] = "$variant {$variantWidth}w";
            }
        }
        $srcset[] = "$src {$width}w";

        $isEnglish = 'en' === $this->requestStack->getCurrentRequest()?->getLocale();

        return [
            'src' => $src,
            'srcset' => implode(', ', $srcset),
            'width' => $width,
            'height' => $height,
            'alt' => ($isEnglish ? $siteImage->getAltEn() : null) ?: $siteImage->getAlt(),
        ];
    }
}
