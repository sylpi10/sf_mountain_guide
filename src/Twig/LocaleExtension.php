<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * locale_urls() : chemin de la page courante dans chaque langue (sélecteur de langue, hreflang).
 * Vide pour les pages sans version par URL (login, newsletter…).
 * Les pages à slug traduit (activité, article, secteur…) fournissent leurs slugs via l'attribut _locale_slugs.
 */
class LocaleExtension extends AbstractExtension
{
    public function __construct(
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator,
        #[Autowire('%app.locales%')] private array $locales,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('locale_urls', [$this, 'getLocaleUrls']),
        ];
    }

    /**
     * @return array<string, string> locale => chemin
     */
    public function getLocaleUrls(): array
    {
        $request = $this->requestStack->getMainRequest();
        $params = $request?->attributes->get('_route_params', []) ?? [];
        // seules les routes traduites portent un _locale (_route y vaut déjà le nom canonique : "about", pas "about.en")
        if (!isset($params['_locale'])) {
            return [];
        }
        $route = $request->attributes->get('_route');
        $slugs = $request->attributes->get('_locale_slugs', []);

        $urls = [];
        foreach ($this->locales as $locale) {
            $localeParams = ['_locale' => $locale] + (isset($slugs[$locale]) ? ['slug' => $slugs[$locale]] : []);
            $urls[$locale] = $this->urlGenerator->generate($route, $localeParams + $params);
        }

        return $urls;
    }
}
