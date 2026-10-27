<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261027120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Pages secteurs : slug de la version anglaise (/en/...) stocké en base, pour créer les pages en admin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE area_page ADD slug_en VARCHAR(80) DEFAULT NULL');

        // slugs EN jusqu'ici en dur dans AreaPage::SLUGS_EN ; repli sur le slug FR pour toute autre page
        $this->addSql('UPDATE area_page SET slug_en = slug');
        foreach ([
            'guide-serre-chevalier' => 'mountain-guide-serre-chevalier',
            'guide-montgenevre' => 'mountain-guide-montgenevre',
            'guide-vallee-de-la-claree' => 'mountain-guide-claree-valley',
        ] as $slug => $slugEn) {
            $this->addSql('UPDATE area_page SET slug_en = :slug_en WHERE slug = :slug', ['slug' => $slug, 'slug_en' => $slugEn]);
        }

        $this->addSql('ALTER TABLE area_page MODIFY slug_en VARCHAR(80) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D64F993F7D79C0DC ON area_page (slug_en)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_D64F993F7D79C0DC ON area_page');
        $this->addSql('ALTER TABLE area_page DROP slug_en');
    }
}
