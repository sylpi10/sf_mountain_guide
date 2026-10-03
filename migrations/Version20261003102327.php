<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003102327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table site_image (images hero, contact, à propos) et ses emplacements';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE site_image (id INT AUTO_INCREMENT NOT NULL, slot VARCHAR(50) NOT NULL, image VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, alt_en VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_167D45AAC0E2067 (slot), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // emplacements fixes, sans image : le site garde ses images statiques tant que rien n'est uploadé
        $this->addSql("INSERT INTO site_image (slot, alt) VALUES
            ('hero', NULL),
            ('hero_mobile', NULL),
            ('contact', NULL),
            ('about_1', 'Photo de guide de montagne en falaise'),
            ('about_2', 'Photo de guide de montagne en campement')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE site_image');
    }
}
