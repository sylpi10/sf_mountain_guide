<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003221244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Discipline : titre Google (seo_title / seo_title_en), prérempli quand le nom correspond';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE discipline ADD seo_title VARCHAR(70) DEFAULT NULL, ADD seo_title_en VARCHAR(70) DEFAULT NULL');

        // préremplissage par nom exact (les textes de prod peuvent différer) : à vérifier en admin
        $titles = [
            'Alpinisme' => ['Alpinisme avec un guide de haute montagne à Briançon', 'Mountaineering with a mountain guide in Briançon'],
            'Escalade' => ['Escalade et grandes voies avec un guide à Briançon', 'Rock climbing and multi-pitch with a guide in Briançon'],
            'Ski' => ['Ski hors-piste et ski de rando à Serre Chevalier, Montgenèvre', 'Off-piste skiing and ski touring in Serre Chevalier'],
            'Cascade de Glace' => ['Cascade de glace avec un guide de haute montagne à Briançon', 'Ice climbing with a mountain guide in Briançon'],
        ];
        foreach ($titles as $name => [$fr, $en]) {
            $this->addSql('UPDATE discipline SET seo_title = ?, seo_title_en = ? WHERE title = ?', [$fr, $en, $name]);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE discipline DROP seo_title, DROP seo_title_en');
    }
}
