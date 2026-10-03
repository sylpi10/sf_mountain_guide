<?php

namespace App\Twig;

use App\Repository\SiteTextRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * site_text('home_intro') : paragraphes du texte dans la langue courante
 * (repli sur le FR si l'EN est vide), ou [] si aucun texte en base.
 */
class SiteTextExtension extends AbstractExtension
{
    private ?array $siteTexts = null;

    public function __construct(
        private SiteTextRepository $repository,
        private RequestStack $requestStack,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('site_text', [$this, 'getSiteText']),
        ];
    }

    /**
     * @return string[]
     */
    public function getSiteText(string $slot): array
    {
        $this->siteTexts ??= $this->repository->findAllIndexedBySlot();
        $siteText = $this->siteTexts[$slot] ?? null;

        if (!$siteText) {
            return [];
        }

        $isEnglish = $this->requestStack->getCurrentRequest()?->getLocale() === 'en';
        $content = ($isEnglish ? $siteText->getContentEn() : null) ?: $siteText->getContent();

        // une ligne vide sépare deux paragraphes
        $paragraphs = preg_split('/\R\s*\R/', trim((string) $content));

        return array_values(array_filter(array_map('trim', $paragraphs)));
    }
}
