<?php

namespace App\Twig;

use App\Entity\LegalPage;
use App\Repository\LegalPageRepository;
use Symfony\Contracts\Service\ResetInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * legal_pages() : pages légales publiées, pour les liens du footer.
 */
class LegalPageExtension extends AbstractExtension implements ResetInterface
{
    private ?array $pages = null;

    public function __construct(private LegalPageRepository $repository)
    {
    }

    // vide le cache mémoire entre deux requêtes (workers longs, tests)
    public function reset(): void
    {
        $this->pages = null;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('legal_pages', [$this, 'getLegalPages']),
        ];
    }

    /**
     * @return LegalPage[]
     */
    public function getLegalPages(): array
    {
        return $this->pages ??= $this->repository->findPublished();
    }
}
