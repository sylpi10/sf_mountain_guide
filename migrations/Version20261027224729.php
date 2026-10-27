<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261027224729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Avis clients (recopiés depuis Google) pour le slider avant le formulaire de contact';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(100) NOT NULL, rating SMALLINT NOT NULL, content LONGTEXT NOT NULL, content_en LONGTEXT DEFAULT NULL, reviewed_at DATE DEFAULT NULL, published TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE review');
    }
}
