<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Groupes d'activités, sorties, et données de départ : groupes Été / Hiver et les 10 disciplines du menu.
 * Disciplines existantes publiées et rangées dans leur groupe (« Ski » renommée « Ski hors-piste »),
 * nouvelles disciplines créées en brouillon (à compléter en admin avant publication).
 */
final class Version20261128135954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Activités en 2 niveaux : groupes de menu (Hiver / Été), disciplines publiables, sorties';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, name_en VARCHAR(80) DEFAULT NULL, season VARCHAR(20) DEFAULT NULL, position INT DEFAULT 0 NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE outing (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, title_en VARCHAR(150) DEFAULT NULL, seo_title VARCHAR(70) DEFAULT NULL, seo_title_en VARCHAR(70) DEFAULT NULL, teaser LONGTEXT DEFAULT NULL, teaser_en LONGTEXT DEFAULT NULL, content LONGTEXT NOT NULL, content_en LONGTEXT DEFAULT NULL, prerequisites LONGTEXT DEFAULT NULL, prerequisites_en LONGTEXT DEFAULT NULL, duration VARCHAR(100) DEFAULT NULL, duration_en VARCHAR(100) DEFAULT NULL, elevation INT DEFAULT NULL, max_people INT DEFAULT NULL, audience VARCHAR(150) DEFAULT NULL, audience_en VARCHAR(150) DEFAULT NULL, period VARCHAR(150) DEFAULT NULL, period_en VARCHAR(150) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, location_en VARCHAR(255) DEFAULT NULL, meeting_point VARCHAR(255) DEFAULT NULL, meeting_point_en VARCHAR(255) DEFAULT NULL, included LONGTEXT DEFAULT NULL, included_en LONGTEXT DEFAULT NULL, not_included LONGTEXT DEFAULT NULL, not_included_en LONGTEXT DEFAULT NULL, equipment_to_bring LONGTEXT DEFAULT NULL, equipment_to_bring_en LONGTEXT DEFAULT NULL, equipment_provided LONGTEXT DEFAULT NULL, equipment_provided_en LONGTEXT DEFAULT NULL, price INT DEFAULT NULL, price_note VARCHAR(150) DEFAULT NULL, price_note_en VARCHAR(150) DEFAULT NULL, level VARCHAR(20) DEFAULT NULL, position INT DEFAULT 0 NOT NULL, is_published TINYINT DEFAULT 0 NOT NULL, featured_on_home TINYINT DEFAULT 0 NOT NULL, image VARCHAR(255) DEFAULT NULL, image_alt VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, discipline_id INT NOT NULL, INDEX IDX_F2A10625A5522701 (discipline_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A10625A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id)');
        $this->addSql('ALTER TABLE discipline ADD position INT DEFAULT 0 NOT NULL, ADD is_published TINYINT DEFAULT 0 NOT NULL, ADD featured_on_home TINYINT DEFAULT 0 NOT NULL, ADD activity_group_id INT DEFAULT NULL, CHANGE pers_number pers_number LONGTEXT DEFAULT NULL, CHANGE price price LONGTEXT DEFAULT NULL, CHANGE english_content english_content LONGTEXT DEFAULT NULL, CHANGE english_nb_pers english_nb_pers LONGTEXT DEFAULT NULL, CHANGE english_duration english_duration VARCHAR(255) DEFAULT NULL, CHANGE english_price english_price VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline ADD CONSTRAINT FK_75BEEE3F5E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_75BEEE3F5E5E6949 ON discipline (activity_group_id)');

        // groupes du menu
        $this->addSql("INSERT INTO activity_group (id, name, name_en, season, position) VALUES (1, 'Hiver', 'Winter', 'winter', 1), (2, 'Été', 'Summer', 'summer', 2)");

        // disciplines déjà en ligne : publiées, rangées dans leur groupe
        $this->addSql('UPDATE discipline SET is_published = 1');
        $this->addSql("UPDATE discipline SET title = 'Ski hors-piste', english_title = 'Off-piste skiing', activity_group_id = 1, position = 2, featured_on_home = 1 WHERE title = 'Ski'");
        $this->addSql("UPDATE discipline SET activity_group_id = 1, position = 3, featured_on_home = 1 WHERE title = 'Cascade de Glace'");
        $this->addSql("UPDATE discipline SET activity_group_id = 2, position = 1, featured_on_home = 1 WHERE title = 'Alpinisme'");
        $this->addSql("UPDATE discipline SET activity_group_id = 2, position = 2, featured_on_home = 1 WHERE title = 'Escalade'");

        // nouvelles disciplines, en brouillon
        $new = [
            // titre, titre EN, groupe, position, mise en avant sur l'accueil
            ['Ski de rando', 'Ski touring', 1, 1, 1],
            ['Voyages à ski', 'Ski trips', 1, 4, 0],
            ['Alpinisme hivernal', 'Winter mountaineering', 1, 5, 0],
            ['Canyoning', 'Canyoning', 2, 3, 0],
            ['Via ferrata', 'Via ferrata', 2, 4, 0],
            ['Randonnée', 'Hiking', 2, 5, 0],
        ];
        foreach ($new as [$title, $titleEn, $group, $position, $featured]) {
            $this->addSql(
                "INSERT INTO discipline (title, english_title, content, english_content, activity_group_id, position, is_published, featured_on_home, updated_at)
                 SELECT ?, ?, '', '', ?, ?, 0, ?, NOW() FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM discipline WHERE title = ?)",
                [$title, $titleEn, $group, $position, $featured, $title],
            );
        }
    }

    public function down(Schema $schema): void
    {
        // disciplines créées par up() (les autres ne sont pas touchées)
        $this->addSql("DELETE FROM discipline WHERE is_published = 0 AND title IN ('Ski de rando', 'Voyages à ski', 'Alpinisme hivernal', 'Canyoning', 'Via ferrata', 'Randonnée') AND id NOT IN (SELECT discipline_id FROM outing)");
        $this->addSql("UPDATE discipline SET title = 'Ski', english_title = 'Ski' WHERE title = 'Ski hors-piste'");
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625A5522701');
        $this->addSql('DROP TABLE activity_group');
        $this->addSql('DROP TABLE outing');
        $this->addSql('ALTER TABLE discipline DROP FOREIGN KEY FK_75BEEE3F5E5E6949');
        $this->addSql('DROP INDEX IDX_75BEEE3F5E5E6949 ON discipline');
        $this->addSql('ALTER TABLE discipline DROP position, DROP is_published, DROP featured_on_home, DROP activity_group_id, CHANGE pers_number pers_number LONGTEXT NOT NULL, CHANGE price price LONGTEXT NOT NULL, CHANGE english_content english_content LONGTEXT NOT NULL, CHANGE english_nb_pers english_nb_pers LONGTEXT NOT NULL, CHANGE english_duration english_duration VARCHAR(255) NOT NULL, CHANGE english_price english_price VARCHAR(255) NOT NULL');
    }
}
