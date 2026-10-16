<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * upload_image(discipline.image, widths) : src, srcset, variants, width, height d'une image de /uploads.
 * Les déclinaisons WebP (photo-123-480.webp…) sont reprises si elles existent ;
 * une ancienne image non convertie (jpg) reste utilisable avec son seul src.
 */
class UploadImageExtension extends AbstractExtension
{
    public function __construct(
        #[Autowire('%app.path.discipline_images%')] private string $publicPath,
        #[Autowire('%kernel.project_dir%/public%app.path.discipline_images%')] private string $dir,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('upload_image', [$this, 'getUploadImage']),
        ];
    }

    /**
     * @param int[] $widths
     */
    public function getUploadImage(?string $fileName, array $widths = []): ?array
    {
        if (!$fileName) {
            return null;
        }

        $src = $this->publicPath . '/' . $fileName;
        $size = @getimagesize($this->dir . '/' . $fileName);
        [$width, $height] = $size ?: [null, null];

        // largeur => url, de la plus petite à la plus grande
        $variants = [];
        if ($width && str_ends_with($fileName, '.webp')) {
            $base = substr($fileName, 0, -5);
            foreach ($widths as $variantWidth) {
                if ($variantWidth < $width && is_file("$this->dir/$base-$variantWidth.webp")) {
                    $variants[$variantWidth] = "$this->publicPath/$base-$variantWidth.webp";
                }
            }
        }
        if ($width) {
            $variants[$width] = $src;
        }
        ksort($variants);

        return [
            'src' => $src,
            'srcset' => implode(', ', array_map(fn ($url, $w) => "$url {$w}w", $variants, array_keys($variants))) ?: null,
            'variants' => $variants,
            'width' => $width,
            'height' => $height,
        ];
    }
}
