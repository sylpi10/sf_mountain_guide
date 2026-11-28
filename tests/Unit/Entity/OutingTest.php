<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Discipline;
use App\Entity\Outing;
use App\Enum\OutingLevel;
use PHPUnit\Framework\TestCase;

class OutingTest extends TestCase
{
    public function testSlugFallsBackToFrenchTitle(): void
    {
        $outing = new Outing()->setTitle('Découverte du ski de rando')->setTitleEn('Ski touring discovery');

        self::assertSame('decouverte-du-ski-de-rando', $outing->getSlug('fr'));
        self::assertSame('ski-touring-discovery', $outing->getSlug('en'));

        $outing->setTitleEn(null);
        self::assertSame('decouverte-du-ski-de-rando', $outing->getSlug('en'));
    }

    public function testListsAreSplitByLineAndFallBackToFrench(): void
    {
        $outing = new Outing()->setIncluded("- Guide UIAGM\r\n\n• Prêt DVA, pelle, sonde\n  ");

        self::assertSame(['Guide UIAGM', 'Prêt DVA, pelle, sonde'], $outing->getListFor('included', 'fr'));
        self::assertSame(['Guide UIAGM', 'Prêt DVA, pelle, sonde'], $outing->getListFor('included', 'en'));
        self::assertSame([], $outing->getListFor('notIncluded', 'fr'));

        $outing->setIncludedEn("IFMGA guide\nAvalanche kit");
        self::assertSame(['IFMGA guide', 'Avalanche kit'], $outing->getListFor('included', 'en'));
    }

    public function testDisciplineListsOnlyPublishedOutings(): void
    {
        $discipline = new Discipline();
        $published = new Outing()->setTitle('A')->setIsPublished(true);
        $draft = new Outing()->setTitle('B');
        $discipline->getOutings()->add($published);
        $discipline->getOutings()->add($draft);

        self::assertSame([$published], $discipline->getPublishedOutings());
    }

    public function testLevelLabels(): void
    {
        self::assertSame('Découverte', OutingLevel::Discovery->label());
        self::assertSame('Beginner', OutingLevel::Discovery->label('en'));
        self::assertSame(4, OutingLevel::Expert->rank());
    }
}
