<?php

namespace App\Twig;

use App\Entity\AreaPage;
use App\Repository\AreaPageRepository;
use Symfony\Contracts\Service\ResetInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * area_pages() : pages secteurs publiées, pour les liens du footer.
 */
class AreaPageExtension extends AbstractExtension implements ResetInterface
{
    private ?array $pages = null;

    public function __construct(private AreaPageRepository $repository)
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
            new TwigFunction('area_pages', [$this, 'getAreaPages']),
        ];
    }

    /**
     * @return AreaPage[]
     */
    public function getAreaPages(): array
    {
        return $this->pages ??= $this->repository->findPublished();
    }
}
