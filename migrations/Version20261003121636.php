<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003121636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table contact_recipient (destinataires du formulaire de contact) avec les adresses actuelles';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE contact_recipient (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(100) DEFAULT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_6F5D832E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // adresses jusqu'ici en dur dans GlobalController
        $this->addSql("INSERT INTO contact_recipient (email, name, is_active) VALUES
            ('contact@directicimes.com', 'Boîte DirectiCimes', 1),
            ('georgesyn@gmail.com', 'Yoann - Gmail', 1)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE contact_recipient');
    }
}
