<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261016171357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Discipline : infos chiffrées de la carte produit (pers. max, prix à partir de), pré-remplies depuis les textes actuels';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE discipline ADD max_people_beginner INT DEFAULT NULL, ADD max_people_technical_min INT DEFAULT NULL, ADD max_people_technical_max INT DEFAULT NULL, ADD price_from INT DEFAULT NULL, ADD price_unit VARCHAR(20) DEFAULT NULL');

        // reprise des textes « Nombre de participants » et « Prix » ; sans effet si le titre diffère
        $this->addSql("UPDATE discipline SET max_people_beginner = 6, max_people_technical_min = 1, max_people_technical_max = 2, price_from = 370, price_unit = 'day' WHERE title = 'Alpinisme'");
        $this->addSql("UPDATE discipline SET max_people_beginner = 8, max_people_technical_max = 2, price_from = 250, price_unit = 'half_day' WHERE title = 'Escalade'");
        $this->addSql("UPDATE discipline SET max_people_beginner = 6, price_from = 370, price_unit = 'day' WHERE title = 'Ski'");
        $this->addSql("UPDATE discipline SET max_people_beginner = 8, max_people_technical_max = 2, price_from = 370, price_unit = 'day' WHERE title = 'Cascade de Glace'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE discipline DROP max_people_beginner, DROP max_people_technical_min, DROP max_people_technical_max, DROP price_from, DROP price_unit');
    }
}
