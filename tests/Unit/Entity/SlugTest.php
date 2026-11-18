<?php

namespace App\Tests\Unit\Entity;

use App\Entity\AreaPage;
use App\Entity\Discipline;
use App\Entity\LegalPage;
use PHPUnit\Framework\TestCase;

class SlugTest extends TestCase
{
    public function testDisciplineSlugFallsBackToFrenchTitle(): void
    {
        $discipline = new Discipline()->setTitle('Cascade de glace')->setEnglishTitle('Ice Climbing');

        self::assertSame('cascade-de-glace', $discipline->getSlug());
        self::assertSame('cascade-de-glace', $discipline->getSlug('fr'));
        self::assertSame('ice-climbing', $discipline->getSlug('en'));

        $discipline->setEnglishTitle('');
        self::assertSame('cascade-de-glace', $discipline->getSlug('en'));
    }

    public function testAreaPageSlugFallsBackToFrenchSlug(): void
    {
        $page = new AreaPage()->setSlug('guide-vallouise')->setSlugEn('mountain-guide-vallouise');

        self::assertSame('guide-vallouise', $page->getSlug('fr'));
        self::assertSame('mountain-guide-vallouise', $page->getSlug('en'));

        $page->setSlugEn(null);
        self::assertSame('guide-vallouise', $page->getSlug('en'));
    }

    public function testLegalPageSlugs(): void
    {
        self::assertSame('cgv', LegalPage::frenchSlug('terms-and-conditions'));
        self::assertSame('mentions-legales', LegalPage::frenchSlug('legal-notice'));
        self::assertSame('cgv', LegalPage::frenchSlug('cgv'));

        // chaque slug FR et EN doit être servi par la route
        foreach (LegalPage::SLUGS_EN as $fr => $en) {
            self::assertMatchesRegularExpression('/^('.LegalPage::SLUG_REQUIREMENT.')$/', $fr);
            self::assertMatchesRegularExpression('/^('.LegalPage::SLUG_REQUIREMENT.')$/', $en);
        }
    }
}
