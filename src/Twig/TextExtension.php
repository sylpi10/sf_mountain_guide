<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('plain_text', [$this, 'plainText']),
        ];
    }

    /**
     * Convertit du HTML riche (éventuellement encodé plusieurs fois) en texte brut sur une ligne.
     */
    public function plainText(?string $html, int $length = 160): string
    {
        // espace avant chaque balise : "</h3><p>Vous" ne doit pas donner "…vigilanceVous"
        $text = preg_replace('/<[^>]*>/', ' $0', (string) $html) ?? (string) $html;
        for ($i = 0; $i < 3; ++$i) {
            $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        $text = preg_replace('/[\s\x{00A0}]+/u', ' ', $text) ?? $text;

        return mb_strimwidth(trim($text), 0, $length, '…', 'UTF-8');
    }
}
