<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Discipline;
use App\Entity\LegalPage;
use App\Entity\AreaPage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SitemapController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    #[Route(path: '/sitemap.xml', name: 'sitemap', defaults: ['_format' => 'xml'])]
    public function index(Request $request)
    {
        // get host name from url
        $hostname = $request->getSchemeAndHttpHost();

        //init url array
        $urls = [];

        // add statics urls
        foreach (["home", "activities", "about", "pricing", "blog"] as $route) {
            $this->addLocalized($urls, $route);
        }
        foreach (
            $this->managerRegistry->getRepository(LegalPage::class)->findBy(["isPublished" => true])
            as $legalPage
        ) {
            $this->addLocalized($urls, "legal_page", fn ($locale) => ["slug" => $legalPage->getSlug($locale)]);
        }
        foreach (
            $this->managerRegistry->getRepository(AreaPage::class)->findBy(["isPublished" => true])
            as $areaPage
        ) {
            $this->addLocalized($urls, "area_page", fn ($locale) => ["slug" => $areaPage->getSlug($locale)]);
        }

        // add dynamic urls
        foreach (
            $this->managerRegistry->getRepository(Discipline::class)->findAll()
            as $discipline
        ) {
            $this->addLocalized($urls, "detail", fn ($locale) => [
                "slug" => $discipline->getSlug($locale),
                "id" => $discipline->getId(),
            ]);
        }

        foreach (
            $this->managerRegistry->getRepository(Blog::class)->findAll()
            as $post
        ) {
            $extra = $post->getUpdatedAt() ? ["lastmod" => $post->getUpdatedAt()->format("Y-m-d")] : [];
            $this->addLocalized($urls, "blog_detail", fn ($locale) => [
                "slug" => $post->getSlug($locale),
                "id" => $post->getId(),
            ], $extra);
        }

        // create response
        $response = new Response(
            $this->renderView("sitemap/index.html.twig", [
                "urls" => $urls,
                "hostname" => $hostname,
            ]),
            200,
        );

        // add headers
        $response->headers->set("Content-Type", "text/xml");

        //send response
        return $response;
    }

    /**
     * Une entrée par langue, chacune listant toutes les versions (balises hreflang du sitemap).
     */
    private function addLocalized(array &$urls, string $route, ?callable $params = null, array $extra = []): void
    {
        $alternates = [];
        foreach ($this->getParameter("app.locales") as $locale) {
            $alternates[$locale] = $this->generateUrl($route, ["_locale" => $locale] + ($params ? $params($locale) : []));
        }
        foreach ($alternates as $loc) {
            $urls[] = ["loc" => $loc, "alternates" => $alternates] + $extra;
        }
    }
}
