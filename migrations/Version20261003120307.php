<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003120307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table site_text (textes modifiables en admin) avec le texte de présentation de l\'accueil';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE site_text (id INT AUTO_INCREMENT NOT NULL, slot VARCHAR(50) NOT NULL, content LONGTEXT DEFAULT NULL, content_en LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_111A3EAC0E2067 (slot), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('INSERT INTO site_text (slot, content, content_en) VALUES (?, ?, ?)', [
            'home_intro',
            "Guide de haute montagne diplômé de l'ENSA (UIAGM), je vous accompagne toute l'année au départ de Briançon, dans les Hautes-Alpes : massif des Écrins, vallée de la Clarée, Serre Chevalier et Queyras.\n\n"
            .'Alpinisme, ski de randonnée et hors-piste, escalade en falaise et en grandes voies, cascade de glace : que vous débutiez ou que vous prépariez un projet ambitieux, je vous propose des sorties sur mesure, en petit groupe ou en privé, adaptées à votre niveau et aux conditions du moment.',
            "IFMGA-certified mountain guide trained at ENSA, I take you out all year round from Briançon, in the French Hautes-Alpes: the Écrins massif, the Clarée valley, Serre Chevalier and the Queyras.\n\n"
            .'Mountaineering, ski touring and off-piste, crag and multi-pitch rock climbing, ice climbing: whether you are a beginner or preparing an ambitious project, I offer tailor-made trips, in small groups or private, adapted to your level and to current conditions.',
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE site_text');
    }
}
