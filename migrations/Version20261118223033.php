<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261118223033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Catégories du blog (filtres en pastilles sur /blog), une catégorie par article';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE blog_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, english_name VARCHAR(60) DEFAULT NULL, position INT NOT NULL, UNIQUE INDEX UNIQ_72113DE65E237E06 (name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blog ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514312469DE2 FOREIGN KEY (category_id) REFERENCES blog_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C015514312469DE2 ON blog (category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514312469DE2');
        $this->addSql('DROP INDEX IDX_C015514312469DE2 ON blog');
        $this->addSql('ALTER TABLE blog DROP category_id');
        $this->addSql('DROP TABLE blog_category');
    }
}
