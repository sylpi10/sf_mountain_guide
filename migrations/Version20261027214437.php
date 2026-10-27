<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261027214437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Page tarifs structurée : sections (onglet hiver / été), blocs et lignes de prix (au lieu d\'un seul texte riche)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE pricing_group (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) DEFAULT NULL, title_en VARCHAR(150) DEFAULT NULL, note LONGTEXT DEFAULT NULL, note_en LONGTEXT DEFAULT NULL, position INT NOT NULL, section_id INT NOT NULL, INDEX IDX_4132CC44D823E37A (section_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE pricing_line (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, label_en VARCHAR(255) DEFAULT NULL, price VARCHAR(60) DEFAULT NULL, price_en VARCHAR(60) DEFAULT NULL, position INT NOT NULL, pricing_group_id INT NOT NULL, INDEX IDX_FA02BE48BE4A29AF (pricing_group_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE pricing_section (id INT AUTO_INCREMENT NOT NULL, season VARCHAR(20) NOT NULL, title VARCHAR(150) DEFAULT NULL, title_en VARCHAR(150) DEFAULT NULL, position INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pricing_group ADD CONSTRAINT FK_4132CC44D823E37A FOREIGN KEY (section_id) REFERENCES pricing_section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pricing_line ADD CONSTRAINT FK_FA02BE48BE4A29AF FOREIGN KEY (pricing_group_id) REFERENCES pricing_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pricing_group DROP FOREIGN KEY FK_4132CC44D823E37A');
        $this->addSql('ALTER TABLE pricing_line DROP FOREIGN KEY FK_FA02BE48BE4A29AF');
        $this->addSql('DROP TABLE pricing_group');
        $this->addSql('DROP TABLE pricing_line');
        $this->addSql('DROP TABLE pricing_section');
    }
}
