<?php

namespace App\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Affiche une icône du sprite public/images/icons.svg.
 */
class IconExtension extends AbstractExtension
{
    private ?string $spriteUrl = null;

    public function __construct(
        private Packages $packages,
        #[Autowire('%kernel.project_dir%/public/images/icons.svg')] private string $spritePath,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [$this, 'icon'], ['is_safe' => ['html']]),
        ];
    }

    public function icon(string $name, string $class = ''): string
    {
        // ?v= : le navigateur recharge le sprite dès qu'une icône est ajoutée
        $this->spriteUrl ??= htmlspecialchars(
            $this->packages->getUrl('images/icons.svg') . '?v=' . @filemtime($this->spritePath),
            ENT_QUOTES
        );
        $sprite = $this->spriteUrl;
        $class = trim('icon ' . htmlspecialchars($class, ENT_QUOTES));

        return sprintf(
            '<svg class="%s" aria-hidden="true" focusable="false"><use href="%s#%s"/></svg>',
            $class,
            $sprite,
            htmlspecialchars($name, ENT_QUOTES)
        );
    }
}
