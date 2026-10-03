<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003194138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table pricing_page (page tarifs, mise à jour à chaque saison)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE pricing_page (id INT AUTO_INCREMENT NOT NULL, season VARCHAR(100) DEFAULT NULL, season_en VARCHAR(100) DEFAULT NULL, content LONGTEXT DEFAULT NULL, content_en LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // contenu de départ minimal, à compléter en admin (conditions, inclus, tarifs de groupe…)
        $this->addSql('INSERT INTO pricing_page (season, season_en, content, content_en) VALUES (?, ?, ?, ?)', [
            'Saison 2026 - 2027',
            '2026 - 2027 season',
            "<p>Les tarifs ci-dessous s'entendent à l'engagement, c'est-à-dire pour le guide et non par personne. Pour un projet sur plusieurs jours, un raid ou un groupe, contactez-moi pour un devis personnalisé.</p>",
            '<p>Prices below are for hiring the guide, not per person. For a multi-day project, a ski tour or a group, contact me for a personalised quote.</p>',
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE pricing_page');
    }
}
