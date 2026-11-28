<?php

namespace App\Twig;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * review_schema() : note moyenne + avis publiés au format schema.org,
 * fusionnés dans les données structurées de l'entreprise (base.html.twig).
 */
class ReviewExtension extends AbstractExtension
{
    private ?array $schema = null;

    public function __construct(private ReviewRepository $repository)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('review_schema', [$this, 'getReviewSchema']),
        ];
    }

    /**
     * @return array{aggregateRating?: array, review?: array}
     */
    public function getReviewSchema(): array
    {
        if (null !== $this->schema) {
            return $this->schema;
        }

        $reviews = $this->repository->findPublished();
        if (!$reviews) {
            return $this->schema = [];
        }

        $average = array_sum(array_map(fn (Review $r) => $r->getRating(), $reviews)) / \count($reviews);

        return $this->schema = [
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => round($average, 1),
                'bestRating' => 5,
                'worstRating' => 1,
                'ratingCount' => \count($reviews),
                'reviewCount' => \count($reviews),
            ],
            'review' => array_map(fn (Review $r) => array_filter([
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => $r->getAuthorName()],
                'datePublished' => $r->getReviewedAt()?->format('Y-m-d'),
                'reviewBody' => $r->getContent(),
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => $r->getRating(),
                    'bestRating' => 5,
                    'worstRating' => 1,
                ],
            ], fn ($v) => null !== $v), $reviews),
        ];
    }
}
