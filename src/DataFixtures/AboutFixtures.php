<?php

namespace App\DataFixtures;

use App\Entity\About;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AboutFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Crée une seule entrée About (singleton)
        $about = new About();

        // Section "Qui sommes-nous" - Français
        $about->setWhoTitle('Qui sommes-nous ?');
        $about->setWhoText('DirectiCimes est une entreprise spécialisée dans l\'organisation d\'activités de plein air dans les Alpes. Nous proposons des expériences uniques en montagne, adaptées à tous les niveaux, pour découvrir la nature autrement.');

        // Section "Qui sommes-nous" - Anglais
        $about->setWhoEnglishTitle('Who are we?');
        $about->setWhoEnglishText('DirectiCimes is a company specializing in outdoor activities in the Alps. We offer unique mountain experiences, suitable for all levels, to discover nature in a different way.');

        // Section "Pourquoi nous choisir" - Français
        $about->setWhyTitle('Pourquoi nous choisir ?');
        $about->setWhyText('Nous offrons des activités encadrées par des professionnels diplômés, avec un équipement de qualité et une approche personnalisée. Notre priorité : votre sécurité et votre plaisir. Découvrez nos activités en petit groupe pour une expérience plus intimiste.');

        // Section "Pourquoi nous choisir" - Anglais
        $about->setWhyEnglishTitle('Why choose us?');
        $about->setWhyEnglishText('We offer activities supervised by qualified professionals, with quality equipment and a personalized approach. Our priority: your safety and enjoyment. Discover our activities in small groups for a more intimate experience.');

        $manager->persist($about);
        $manager->flush();

        // Optionnel : référence pour d'autres fixtures
        $this->addReference('about', $about);
    }
}
