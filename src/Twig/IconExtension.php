<?php

namespace App\Twig;

use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Affiche une icône du sprite public/images/icons.svg.
 */
class IconExtension extends AbstractExtension
{
    public function __construct(private Packages $packages)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [$this, 'icon'], ['is_safe' => ['html']]),
        ];
    }

    public function icon(string $name, string $class = ''): string
    {
        $sprite = htmlspecialchars($this->packages->getUrl('images/icons.svg'), ENT_QUOTES);
        $class = trim('icon ' . htmlspecialchars($class, ENT_QUOTES));

        return sprintf(
            '<svg class="%s" aria-hidden="true" focusable="false"><use href="%s#%s"/></svg>',
            $class,
            $sprite,
            htmlspecialchars($name, ENT_QUOTES)
        );
    }
}
