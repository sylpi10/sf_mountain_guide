<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209174952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline ADD english_title VARCHAR(255) NOT NULL, ADD english_content VARCHAR(255) NOT NULL, ADD english_nb_pers LONGTEXT NOT NULL, ADD english_duration VARCHAR(255) NOT NULL, ADD english_location LONGTEXT DEFAULT NULL, ADD english_price VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline DROP english_title, DROP english_content, DROP english_nb_pers, DROP english_duration, DROP english_location, DROP english_price');
    }
}
