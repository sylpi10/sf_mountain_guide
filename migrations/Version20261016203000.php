<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261016203000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Page Activités : accroche courte par discipline et texte d\'introduction modifiable en admin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE discipline ADD teaser LONGTEXT DEFAULT NULL, ADD teaser_en LONGTEXT DEFAULT NULL');

        // accroches de départ ; sans effet si le titre diffère
        $teasers = [
            'Alpinisme' => [
                "Randonnée glaciaire, première course d'arête ou sommet mythique des Écrins : apprenez les bases ou visez plus haut, encordé avec votre guide.",
                'Glacier walks, your first ridge climb or a legendary Écrins summit: learn the ropes or aim higher, roped up with your guide.',
            ],
            'Escalade' => [
                'Des falaises école aux grandes voies, découvrez la grimpe ou progressez en toute sécurité, dans le Briançonnais et au-delà.',
                'From beginner crags to multi-pitch routes, discover climbing or level up safely, around Briançon and beyond.',
            ],
            'Ski' => [
                'Hors-piste et ski de randonnée : poudreuse, grands espaces et itinéraires choisis selon la neige du jour et votre niveau.',
                'Off-piste and ski touring: powder, wide-open spaces and routes picked to match the snow of the day and your level.',
            ],
            'Cascade de Glace' => [
                'Quelques semaines par an, le froid sculpte des lignes de glace éphémères. Piolets, crampons et sensations garanties !',
                'For a few weeks each winter, the cold sculpts fleeting lines of ice. Ice axes, crampons and guaranteed thrills!',
            ],
        ];
        foreach ($teasers as $title => [$fr, $en]) {
            $this->addSql('UPDATE discipline SET teaser = ?, teaser_en = ? WHERE title = ?', [$fr, $en, $title]);
        }

        $this->addSql('INSERT INTO site_text (slot, content, content_en) VALUES (?, ?, ?)', [
            'activities_intro',
            "Été comme hiver, la montagne change de visage, et il y a toujours une belle aventure à vivre. Gravir votre premier sommet glaciaire, enchaîner les longueurs au soleil, tracer dans la poudreuse ou planter les piolets dans une cascade gelée : à chaque saison son terrain de jeu.\n\n"
            . "Débutant curieux ou montagnard aguerri, on construit ensemble une sortie à votre mesure, selon votre niveau, vos envies et les conditions du moment. En petit groupe ou en privé, avec la sécurité comme priorité et le plaisir comme objectif.\n\n"
            . "Découvrez les activités ci-dessous. Une hésitation, un projet un peu fou ? Écrivez-moi, on en parle !",
            "Summer or winter, the mountains change their face, and there is always a great adventure waiting. Climb your first glaciated summit, link pitches in the sunshine, carve through fresh powder or swing your ice axes into a frozen waterfall: every season has its playground.\n\n"
            . "Curious beginner or seasoned mountaineer, we build a trip that suits you, based on your level, your wishes and current conditions. In a small group or private, with safety as the priority and fun as the goal.\n\n"
            . "Explore the activities below. Not sure which one to pick, or dreaming of a wild project? Drop me a line and let's talk!",
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM site_text WHERE slot = 'activities_intro'");
        $this->addSql('ALTER TABLE discipline DROP teaser, DROP teaser_en');
    }
}
