<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261003210000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Page légale « Mentions légales » (brouillon non publié)';
    }

    public function up(Schema $schema): void
    {
        // brouillon NON publié : relire et compléter les [À compléter] avant de cocher « Publiée »
        $draft = <<<'HTML'
            <h1>Éditeur du site</h1><div>Yoann Georges - Guide de haute montagne (DirectiCimes)<br>12D Rue des 4 Vents, 05100 Villar-Saint-Pancrace, France<br>Téléphone : +33 6 45 98 45 68<br>[À compléter : statut juridique, numéro SIRET, adresse e-mail de contact]</div>
            <h1>Directeur de la publication</h1><div>Yoann Georges</div>
            <h1>Hébergement</h1><div>o2switch - Chemin des Pardiaux, 63000 Clermont-Ferrand, France - www.o2switch.fr<br>[À vérifier : coordonnées de l'hébergeur]</div>
            <h1>Conception et réalisation</h1><div>S. Pillet - <a href="https://sylvainpillet.com/">sylvainpillet.com</a></div>
            <h1>Activité réglementée</h1><div>Guide de haute montagne diplômé (UIAGM / IFMGA).<br>[À compléter : numéro de carte professionnelle d'éducateur sportif, assureur et numéro du contrat de responsabilité civile professionnelle]</div>
            <h1>Propriété intellectuelle</h1><div>Les textes, photos et logos de ce site sont la propriété de Yoann Georges, sauf mention contraire. Toute reproduction sans autorisation est interdite.</div>
            <h1>Données personnelles</h1><div>Les informations envoyées via le formulaire de contact (nom, e-mail, message) ou lors de l'inscription à la newsletter servent uniquement à répondre à votre demande et à vous envoyer des nouvelles. Elles ne sont pas transmises à des tiers.<br>Conformément au RGPD, vous pouvez demander l'accès, la rectification ou la suppression de vos données en écrivant à [À compléter : adresse e-mail].<br>[À compléter : durée de conservation des messages et des inscriptions]</div>
            <h1>Cookies</h1><div>Ce site n'utilise aucun cookie publicitaire ni de mesure d'audience. Seul un cookie technique de session est déposé, pour le fonctionnement des formulaires, de la connexion et du choix de la langue.</div>
            HTML;

        $this->addSql('INSERT INTO legal_page (slug, title, title_en, content, is_published) VALUES (?, ?, ?, ?, 0)', [
            'mentions-legales',
            'Mentions légales',
            'Legal notice',
            $draft,
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM legal_page WHERE slug = 'mentions-legales'");
    }
}
