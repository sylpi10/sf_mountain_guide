<?php

namespace App\Controller;

use App\Repository\DisciplineRepository;
use App\Repository\OutingRepository;
use App\Service\ContactFormHandler;
use App\Service\SeasonResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GlobalController extends AbstractController
{
    public function __construct(
        private DisciplineRepository $disciplineRepo,
        private OutingRepository $outingRepo,
        private SeasonResolver $seasons,
        private ContactFormHandler $contactForm,
    ) {
    }

    #[Route(path: ['fr' => '/', 'en' => '/en'], name: 'home')]
    public function index(Request $request): Response
    {
        $contact = $this->contactForm->handle(
            $request,
            $this->generateUrl('home', ['_fragment' => 'contact-form']),
        );
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        // mise en avant de la saison en cours (hiver : ski, cascade… ; été : alpinisme, escalade…)
        $season = $this->seasons->current();

        return $this->render('global/index.html.twig', [
            'season' => $season,
            'disciplines' => $this->disciplineRepo->findFeatured($season),
            'outings' => $this->outingRepo->findFeatured($season),
            'displayBtn' => true,
        ] + $contact);
    }

    /**
     * Changement de langue pour les pages sans version par URL (login, newsletter…) ;
     * les pages traduites passent directement par leur URL /en.
     *
     * @param [type] $locale
     */
    #[Route(
        path: '/change-locale/{locale}',
        name: 'change-locale',
        requirements: ['locale' => 'en|fr'],
    ),]
    public function changeLocale($locale, Request $request)
    {
        // stock lang in session
        $request->getSession()->set('_locale', $locale);

        // back to previous page, or home if no referer (e.g. direct/crawler request)
        return $this->redirect(
            $request->headers->get('referer') ?: $this->generateUrl('home'),
        );
    }
}
