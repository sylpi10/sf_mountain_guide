<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261027223331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Page tarifs : mois de bascule des onglets hiver / été (hiver d\'octobre à avril par défaut)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pricing_page ADD winter_from_month SMALLINT DEFAULT 10 NOT NULL, ADD summer_from_month SMALLINT DEFAULT 5 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pricing_page DROP winter_from_month, DROP summer_from_month');
    }
}
