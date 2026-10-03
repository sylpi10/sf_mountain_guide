<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003212442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table area_page (pages secteurs pour le SEO local) avec 3 brouillons non publiés : Serre Chevalier, Montgenèvre, Clarée';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE area_page (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(80) NOT NULL, name VARCHAR(80) NOT NULL, title VARCHAR(150) NOT NULL, title_en VARCHAR(150) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_description_en VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, content_en LONGTEXT DEFAULT NULL, is_published TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_D64F993F989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // brouillons NON publiés, axés ski : à relire par le guide avant de cocher « Publiée »
        $this->addSql('INSERT INTO area_page (slug, name, title, title_en, meta_description, meta_description_en, content, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, 0)', [
            'guide-serre-chevalier',
            'Serre Chevalier',
            'Guide de ski hors-piste et ski de randonnée à Serre Chevalier',
            'Off-piste and ski touring guide in Serre Chevalier',
            'Guide de haute montagne UIAGM à Serre Chevalier : ski hors-piste dans les mélèzes, ski de randonnée en vallée de la Guisane. Sorties sur mesure.',
            'IFMGA mountain guide in Serre Chevalier: off-piste skiing in the larch forests and ski touring in the Guisane valley. Tailor-made days.',
            <<<'HTML'
            <div>Basé à Briançon, au pied du domaine de Serre Chevalier, je vous guide sur les plus belles pentes de la vallée de la Guisane, de Briançon au Monêtier-les-Bains en passant par Chantemerle et Villeneuve.</div>
            <h1>Ski hors-piste à Serre Chevalier</h1><div>Forêts de mélèzes, combes et couloirs : dès que l'on quitte les pistes, le domaine de Serre Chevalier devient un terrain de jeu exceptionnel. Je choisis chaque itinéraire selon l'enneigement, le risque d'avalanche et votre niveau, pour skier la meilleure neige en sécurité.</div>
            <h1>Ski de randonnée autour de la Guisane</h1><div>Des premières sorties en peaux de phoque aux sommets plus engagés aux portes du massif des Écrins, je vous accompagne pour découvrir la randonnée à ski ou progresser : technique de montée, conversions, choix d'itinéraire et utilisation du matériel de sécurité (DVA, pelle, sonde).</div>
            <h1>Pour qui ?</h1><div>Skieurs à l'aise sur piste qui veulent découvrir le hors-piste, randonneurs à la recherche de nouveaux itinéraires, familles ou groupes d'amis : chaque sortie est construite sur mesure, à la journée ou sur plusieurs jours.</div>
            HTML,
        ]);

        $this->addSql('INSERT INTO area_page (slug, name, title, title_en, meta_description, meta_description_en, content, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, 0)', [
            'guide-montgenevre',
            'Montgenèvre',
            'Guide de ski hors-piste et ski de randonnée à Montgenèvre',
            'Off-piste and ski touring guide in Montgenèvre',
            'Guide de haute montagne UIAGM à Montgenèvre : ski hors-piste sur la Voie Lactée, ski de randonnée vers le Chaberton. Sorties sur mesure depuis Briançon.',
            'IFMGA mountain guide in Montgenèvre: off-piste skiing in the Milky Way ski area and ski touring to the Chaberton. Tailor-made days from Briançon.',
            <<<'HTML'
            <div>À quelques minutes de Briançon, Montgenèvre est une station frontalière réputée pour son enneigement, reliée au domaine franco-italien de la Voie Lactée.</div>
            <h1>Ski hors-piste sur la Voie Lactée</h1><div>Entre France et Italie, le domaine offre de grands espaces hors-piste, des pentes ouvertes aux passages en sous-bois. Je vous emmène y chercher la poudreuse en fonction des conditions du jour, en toute sécurité.</div>
            <h1>Ski de randonnée vers le Chaberton</h1><div>Sommet emblématique qui domine Montgenèvre et son ancien fort, le Chaberton est une course de ski de randonnée incontournable. Je vous le propose parmi d'autres itinéraires autour du col, choisis selon votre niveau et la neige.</div>
            <h1>Une sortie à votre mesure</h1><div>Initiation au hors-piste, perfectionnement, journée de ski de randonnée ou raid de plusieurs jours : je construis la sortie avec vous et je m'occupe du choix de l'itinéraire et de la sécurité.</div>
            HTML,
        ]);

        $this->addSql('INSERT INTO area_page (slug, name, title, title_en, meta_description, meta_description_en, content, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, 0)', [
            'guide-vallee-de-la-claree',
            'Vallée de la Clarée',
            'Guide de ski de randonnée dans la vallée de la Clarée',
            'Ski touring guide in the Clarée valley',
            'Guide de haute montagne UIAGM dans la vallée de la Clarée : ski de randonnée autour de Névache, nuits en refuge, Mont Thabor. Sorties et raids sur mesure.',
            'IFMGA mountain guide in the Clarée valley: ski touring around Névache, hut-to-hut trips and the Mont Thabor. Tailor-made days and tours.',
            <<<'HTML'
            <div>Vallée préservée au nord de Briançon, la Clarée est un paradis pour le ski de randonnée : pas de grande station, des villages de caractère comme Névache, et une multitude de sommets accessibles en peaux de phoque.</div>
            <h1>Ski de randonnée autour de Névache</h1><div>Des pentes douces idéales pour débuter aux sommets plus soutenus, la vallée offre des itinéraires pour tous les niveaux. Je vous apprends à lire le terrain, à gérer votre effort et à évoluer en sécurité en montagne l'hiver.</div>
            <h1>Raids et nuits en refuge</h1><div>Avec ses refuges, comme ceux de Laval ou des Drayères, la Clarée se prête aux raids de plusieurs jours, jusqu'au Mont Thabor. Une façon unique de vivre la montagne en hiver, loin de l'agitation des stations.</div>
            <h1>Pour qui ?</h1><div>Débutants en ski de randonnée, skieurs confirmés ou groupes d'amis : je vous propose des sorties à la journée ou des raids sur mesure, adaptés à votre niveau et aux conditions de neige.</div>
            HTML,
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE area_page');
    }
}
