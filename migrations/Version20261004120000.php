<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261004120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'AreaPage : titre Google (seo_title / seo_title_en), les H1 + « - Directicimes » dépassant 60 caractères';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE area_page ADD seo_title VARCHAR(70) DEFAULT NULL, ADD seo_title_en VARCHAR(70) DEFAULT NULL');

        $titles = [
            'guide-serre-chevalier' => ['Guide ski hors-piste et ski de rando à Serre Chevalier', 'Off-piste and ski touring guide in Serre Chevalier'],
            'guide-montgenevre' => ['Guide ski hors-piste et ski de rando à Montgenèvre', 'Off-piste and ski touring guide in Montgenèvre'],
            'guide-vallee-de-la-claree' => ['Guide de ski de randonnée dans la vallée de la Clarée', null],
        ];
        foreach ($titles as $slug => [$fr, $en]) {
            $this->addSql('UPDATE area_page SET seo_title = ?, seo_title_en = ? WHERE slug = ?', [$fr, $en, $slug]);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE area_page DROP seo_title, DROP seo_title_en');
    }
}
