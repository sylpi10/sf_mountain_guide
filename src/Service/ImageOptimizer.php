<?php

namespace App\Service;

/**
 * Redimensionne une image uploadée et la convertit en WebP (GD).
 *
 * Pour une cible "photo-123.webp" et des largeurs [500, 1000] :
 *   - photo-123.webp     : la plus grande taille (max des largeurs, sans agrandir)
 *   - photo-123-500.webp : chaque largeur plus petite que la principale
 */
class ImageOptimizer
{
    private const QUALITY = 80;

    /**
     * @param int[] $widths
     */
    public function process(string $sourcePath, string $targetPath, array $widths, ?float $ratio = null): void
    {
        // une photo d'appareil de 24 Mpx décompressée dépasse vite 128M
        @ini_set('memory_limit', '512M');

        $image = $this->load($sourcePath);

        if ($ratio) {
            $image = $this->crop($image, $ratio);
        }

        $mainWidth = min(imagesx($image), max($widths));
        $base = $this->basePath($targetPath);

        foreach ($widths as $width) {
            if ($width < $mainWidth) {
                $this->save($image, $width, "$base-$width.webp");
            }
        }

        $this->save($image, $mainWidth, $targetPath);
    }

    /**
     * Supprime l'image principale et toutes ses déclinaisons.
     */
    public function delete(string $path): void
    {
        $this->deleteVariants($path);

        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Supprime seulement les déclinaisons (photo-123-500.webp…), pas l'image principale.
     */
    public function deleteVariants(string $path): void
    {
        foreach (glob($this->basePath($path) . '-[0-9]*.webp') ?: [] as $variant) {
            @unlink($variant);
        }
    }

    private function load(string $path): \GdImage
    {
        $image = @imagecreatefromstring((string) file_get_contents($path));

        if (!$image) {
            throw new \RuntimeException('Format d\'image non supporté.');
        }

        // photos de téléphone : appliquer la rotation EXIF
        if (function_exists('exif_read_data') && (getimagesize($path)['mime'] ?? null) === 'image/jpeg') {
            $angle = match (@exif_read_data($path)['Orientation'] ?? 1) {
                3 => 180,
                6 => -90,
                8 => 90,
                default => 0,
            };

            if ($angle) {
                $image = imagerotate($image, $angle, 0);
            }
        }

        imagepalettetotruecolor($image);

        return $image;
    }

    private function crop(\GdImage $image, float $ratio): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width / $height > $ratio) {
            $cropWidth = (int) round($height * $ratio);
            $cropHeight = $height;
        } else {
            $cropWidth = $width;
            $cropHeight = (int) round($width / $ratio);
        }

        return imagecrop($image, [
            'x' => intdiv($width - $cropWidth, 2),
            'y' => intdiv($height - $cropHeight, 2),
            'width' => $cropWidth,
            'height' => $cropHeight,
        ]);
    }

    private function save(\GdImage $source, int $width, string $path): void
    {
        $height = (int) round(imagesy($source) * $width / imagesx($source));

        $resized = imagecreatetruecolor($width, $height);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $width, $height, imagesx($source), imagesy($source));

        if (!imagewebp($resized, $path, self::QUALITY)) {
            throw new \RuntimeException(sprintf('Impossible d\'écrire "%s".', $path));
        }
    }

    private function basePath(string $path): string
    {
        return preg_replace('/\.webp$/', '', $path);
    }
}
