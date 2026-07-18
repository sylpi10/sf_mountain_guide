<?php

namespace App\DataFixtures;

use App\Entity\Discipline;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DisciplinesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $disciplines = [
            [
                "title" => "Escalade découverte",
                "content" =>
                    "Découvrez les bases de l’escalade sur des falaises adaptées aux débutants.",
                "pers_number" => "2 à 8 personnes",
                "duration" => "3 heures",
                "location" => "Vallée de Chamonix",
                "price" => "50 €",
                "english_title" => "Climbing discovery",
                "english_content" =>
                    "Discover the basics of climbing on beginner-friendly cliffs.",
                "english_nb_pers" => "2 to 8 people",
                "english_duration" => "3 hours",
                "english_location" => "Chamonix valley",
                "english_price" => "50 €",
                "image" => "escalade.jpg",
                "image_detail" => "escalade-detail.jpg",
            ],
            [
                "title" => "Randonnée montagne",
                "content" =>
                    "Une randonnée accompagnée pour découvrir les paysages alpins.",
                "pers_number" => "1 à 10 personnes",
                "duration" => "5 heures",
                "location" => "Massif du Mont-Blanc",
                "price" => "40 €",
                "english_title" => "Mountain hiking",
                "english_content" =>
                    "A guided hike to discover alpine landscapes.",
                "english_nb_pers" => "1 to 10 people",
                "english_duration" => "5 hours",
                "english_location" => "Mont-Blanc area",
                "english_price" => "40 €",
                "image" => "rando.jpg",
                "image_detail" => "rando-detail.jpg",
            ],
        ];

        foreach ($disciplines as $data) {
            $discipline = new Discipline();

            $discipline->setTitle($data["title"]);
            $discipline->setContent($data["content"]);
            $discipline->setPersNumber($data["pers_number"]);
            $discipline->setDuration($data["duration"]);
            $discipline->setLocation($data["location"]);
            $discipline->setPrice($data["price"]);

            $discipline->setEnglishTitle($data["english_title"]);
            $discipline->setEnglishContent($data["english_content"]);
            $discipline->setEnglishNbPers($data["english_nb_pers"]);
            $discipline->setEnglishDuration($data["english_duration"]);
            $discipline->setEnglishLocation($data["english_location"]);
            $discipline->setEnglishPrice($data["english_price"]);

            $discipline->setUpdatedAt(new \DateTimeImmutable());

            $discipline->setImage($data["image"]);
            $discipline->setImageDetail($data["image_detail"]);

            $manager->persist($discipline);
        }

        $manager->flush();
    }
}
