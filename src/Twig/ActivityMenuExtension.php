<?php

namespace App\Twig;

use App\Entity\ActivityGroup;
use App\Repository\ActivityGroupRepository;
use App\Service\SeasonResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * activity_groups() : groupes du menu Activités (saison en cours en tête), current_season() : saison en cours.
 */
class ActivityMenuExtension extends AbstractExtension
{
    /** @var ActivityGroup[]|null */
    private ?array $groups = null;

    public function __construct(
        private ActivityGroupRepository $groupRepo,
        private SeasonResolver $seasons,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('activity_groups', [$this, 'getGroups']),
            new TwigFunction('current_season', [$this->seasons, 'current']),
        ];
    }

    /**
     * @return ActivityGroup[]
     */
    public function getGroups(): array
    {
        return $this->groups ??= $this->groupRepo->findForMenu($this->seasons->current());
    }
}
