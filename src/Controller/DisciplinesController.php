<?php

namespace App\Controller;

use App\Entity\Discipline;
use App\Entity\Outing;
use App\Repository\ActivityGroupRepository;
use App\Repository\OutingRepository;
use App\Service\ContactFormHandler;
use App\Service\SeasonResolver;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Activités : liste par groupe (Hiver, Été…) > discipline (page catégorie) > sortie (fiche détaillée).
 */
class DisciplinesController extends AbstractController
{
    public function __construct(
        private ContactFormHandler $contactForm,
    ) {
    }

    #[Route(path: ['fr' => '/activites', 'en' => '/en/activities'], name: 'activities')]
    public function index(Request $request, ActivityGroupRepository $groupRepo, SeasonResolver $seasons): Response
    {
        $contact = $this->contactForm->handle(
            $request,
            $this->generateUrl('activities', ['_fragment' => 'contact-form']),
        );
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        return $this->render('disciplines/index.html.twig', [
            'groups' => $groupRepo->findForMenu($seasons->current()),
        ] + $contact);
    }

    #[Route(
        path: ['fr' => '/activites/{slug}-{id}', 'en' => '/en/activities/{slug}-{id}'],
        name: 'discipline',
        requirements: ['slug' => '[a-z0-9\-]*', 'id' => '\d+'],
    )]
    public function discipline(
        Request $request,
        #[MapEntity(id: 'id')] Discipline $discipline,
        string $slug,
    ): Response {
        $this->denyUnpublished($discipline->isPublished());

        $locale = $request->getLocale();
        if ($discipline->getSlug($locale) !== $slug) {
            return $this->redirectToRoute('discipline', ['id' => $discipline->getId(), 'slug' => $discipline->getSlug($locale)], 301);
        }
        // slugs des autres langues, pour le sélecteur de langue et les hreflang
        $request->attributes->set('_locale_slugs', [
            'fr' => $discipline->getSlug('fr'),
            'en' => $discipline->getSlug('en'),
        ]);

        $contact = $this->contactForm->handle(
            $request,
            $this->generateUrl('discipline', ['id' => $discipline->getId(), 'slug' => $slug, '_fragment' => 'contact-form']),
        );
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        return $this->render('disciplines/detail.html.twig', [
            'discipline' => $discipline,
            'outings' => $this->isGranted('ROLE_ADMIN') ? $discipline->getOutings()->toArray() : $discipline->getPublishedOutings(),
            'displayBtn' => true,
        ] + $contact);
    }

    #[Route(
        path: ['fr' => '/activites/{disciplineSlug}/{slug}-{id}', 'en' => '/en/activities/{disciplineSlug}/{slug}-{id}'],
        name: 'outing',
        requirements: ['disciplineSlug' => '[a-z0-9\-]*', 'slug' => '[a-z0-9\-]*', 'id' => '\d+'],
    )]
    public function outing(
        Request $request,
        #[MapEntity(id: 'id')] Outing $outing,
        string $disciplineSlug,
        string $slug,
        OutingRepository $outingRepo,
    ): Response {
        $discipline = $outing->getDiscipline();
        $this->denyUnpublished($outing->isPublished() && $discipline->isPublished());

        $locale = $request->getLocale();
        if ($outing->getSlug($locale) !== $slug || $discipline->getSlug($locale) !== $disciplineSlug) {
            return $this->redirectToRoute('outing', $this->outingParams($outing, $locale), 301);
        }
        $request->attributes->set('_locale_slugs', [
            'fr' => $this->outingParams($outing, 'fr'),
            'en' => $this->outingParams($outing, 'en'),
        ]);

        $contact = $this->contactForm->handle(
            $request,
            $this->generateUrl('outing', $this->outingParams($outing, $locale) + ['_fragment' => 'contact-form']),
        );
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        return $this->render('outing/detail.html.twig', [
            'outing' => $outing,
            'discipline' => $discipline,
            'similar' => $outingRepo->findSimilar($outing),
            'displayBtn' => true,
        ] + $contact);
    }

    /**
     * Anciennes adresses /discipline/{slug}-{id} (avant les sorties) : vers la page de la discipline.
     */
    #[Route(
        path: ['fr' => '/discipline/{slug}-{id}', 'en' => '/en/activity/{slug}-{id}'],
        name: 'detail',
        requirements: ['slug' => '[a-z0-9\-]*', 'id' => '\d+'],
    )]
    public function legacyDetail(Request $request, #[MapEntity(id: 'id')] Discipline $discipline): RedirectResponse
    {
        return $this->redirectToRoute('discipline', ['id' => $discipline->getId(), 'slug' => $discipline->getSlug($request->getLocale())], 301);
    }

    /**
     * @return array{disciplineSlug: string, slug: string, id: int|null}
     */
    private function outingParams(Outing $outing, string $locale): array
    {
        return [
            'disciplineSlug' => $outing->getDiscipline()->getSlug($locale),
            'slug' => $outing->getSlug($locale),
            'id' => $outing->getId(),
        ];
    }

    // brouillon : 404 pour les visiteurs, aperçu pour l'admin
    private function denyUnpublished(bool $published): void
    {
        if (!$published && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException();
        }
    }
}
