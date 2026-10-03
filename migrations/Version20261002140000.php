<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * La table discipline en prod n'a ni clé primaire ni AUTO_INCREMENT sur id.
 * Sans effet sur une base où la clé existe déjà.
 */
final class Version20261002140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute la clé primaire et l\'AUTO_INCREMENT manquants sur discipline.id';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf(
            $schema->getTable('discipline')->getPrimaryKey() !== null,
            'discipline a déjà une clé primaire'
        );

        $this->addSql('ALTER TABLE discipline CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // Pas de retour arrière : on ne veut pas réintroduire une table sans clé primaire.
    }
}
