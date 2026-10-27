<?php

namespace App\Controller;

use App\Enum\PricingSeason;
use App\Repository\DisciplineRepository;
use App\Repository\PricingPageRepository;
use App\Repository\PricingSectionRepository;
use App\Service\ContactFormHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PricingController extends AbstractController
{
    #[Route(path: ['fr' => '/tarifs', 'en' => '/en/prices'], name: 'pricing')]
    public function index(Request $request, PricingPageRepository $pricingRepo, PricingSectionRepository $sectionRepo, DisciplineRepository $disciplineRepo, ContactFormHandler $contactForm): Response
    {
        $contact = $contactForm->handle($request, $this->generateUrl('pricing', ['_fragment' => 'contact-form']));
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        // un onglet par saison ayant des sections, dans l'ordre de l'enum (hiver, été)
        $allSections = $sectionRepo->findForPage();
        $tabs = [];
        foreach (PricingSeason::cases() as $season) {
            $sections = array_values(array_filter($allSections, fn ($section) => $section->getSeason() === $season));
            if ($sections) {
                $tabs[$season->value] = ['season' => $season, 'sections' => $sections];
            }
        }
        $pricing = $pricingRepo->findPage();
        $active = ($pricing ? PricingSeason::current($pricing->getWinterFromMonth(), $pricing->getSummerFromMonth()) : PricingSeason::current())->value;

        return $this->render('pricing/index.html.twig', [
            'pricing' => $pricing,
            'tabs' => $tabs,
            'activeTab' => isset($tabs[$active]) ? $active : array_key_first($tabs),
            'disciplines' => $disciplineRepo->findAll(),
            'displayBtn' => true,
        ] + $contact);
    }
}
