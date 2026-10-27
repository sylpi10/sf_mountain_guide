<?php

namespace App\Twig\Components;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Slider des avis clients, affiché juste avant le formulaire de contact (<twig:Reviews />).
 * Rien n'est affiché tant qu'aucun avis n'est publié.
 */
#[AsTwigComponent]
class Reviews
{
    public function __construct(
        private ReviewRepository $repository,
        private RequestStack $requestStack,
    ) {
    }

    /** @return Review[] */
    public function getReviews(): array
    {
        return $this->repository->findPublished();
    }

    public function isEn(): bool
    {
        return $this->requestStack->getCurrentRequest()?->getLocale() === 'en';
    }

    public function getText(Review $review): string
    {
        return (string) (($this->isEn() ? $review->getContentEn() : null) ?: $review->getContent());
    }

    // « octobre 2025 » / « October 2025 »
    public function getDate(Review $review): ?string
    {
        $date = $review->getReviewedAt();
        if (!$date) {
            return null;
        }
        if (!class_exists(\IntlDateFormatter::class)) {
            return $date->format('m/Y');
        }
        $formatter = new \IntlDateFormatter($this->isEn() ? 'en' : 'fr', \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'LLLL yyyy');

        return $formatter->format($date) ?: $date->format('m/Y');
    }
}
