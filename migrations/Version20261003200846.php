<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003200846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table legal_page (pages légales éditables) avec un brouillon de CGV non publié';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE legal_page (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(50) NOT NULL, title VARCHAR(150) NOT NULL, title_en VARCHAR(150) DEFAULT NULL, content LONGTEXT DEFAULT NULL, content_en LONGTEXT DEFAULT NULL, is_published TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_39715897989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // brouillon NON publié : plan à compléter par le guide avant de cocher « Publiée »
        $draft = <<<'HTML'
            <h1>1. Objet</h1><div>[À compléter : prestations concernées (encadrement en alpinisme, ski de randonnée, escalade, cascade de glace) et acceptation des CGV lors de la réservation.]</div>
            <h1>2. Prestataire</h1><div>[À compléter : nom, statut (micro-entreprise…), SIRET, adresse, numéro de carte professionnelle d'éducateur sportif, diplôme de guide de haute montagne UIAGM, assureur et numéro du contrat de responsabilité civile professionnelle.]</div>
            <h1>3. Réservation et acompte</h1><div>[À compléter : modalités de réservation, montant de l'acompte, moment où la réservation est ferme.]</div>
            <h1>4. Prix et paiement</h1><div>[À compléter : tarifs en vigueur (voir la page Tarifs), ce qui est compris et non compris (frais de refuge, remontées, transport du guide…), moyens et échéances de paiement.]</div>
            <h1>5. Annulation par le client</h1><div>[À compléter : délais et frais d'annulation, remboursement de l'acompte.]</div>
            <h1>6. Annulation ou modification par le guide</h1><div>[À compléter : conditions météo ou de terrain, sécurité du groupe, niveau inadapté ; solution proposée (report, autre itinéraire, remboursement).]</div>
            <h1>7. Engagements du participant</h1><div>[À compléter : niveau et condition physique, état de santé, matériel personnel, respect des consignes du guide.]</div>
            <h1>8. Assurances</h1><div>[À compléter : assurance du guide ; assurance recommandée au participant (secours, rapatriement, annulation).]</div>
            <h1>9. Médiation et droit applicable</h1><div>[À compléter : coordonnées du médiateur de la consommation (obligatoire), droit français, tribunal compétent.]</div>
            HTML;

        $this->addSql('INSERT INTO legal_page (slug, title, title_en, content, is_published) VALUES (?, ?, ?, ?, 0)', [
            'cgv',
            'Conditions générales de vente',
            'Terms and conditions of sale',
            $draft,
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE legal_page');
    }
}
