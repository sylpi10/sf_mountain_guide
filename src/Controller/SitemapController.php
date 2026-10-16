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
        // $urls[] = $urls->push()
        $urls[] = ["loc" => $this->generateUrl("home")];
        $urls[] = ["loc" => $this->generateUrl("about")];
        $urls[] = ["loc" => $this->generateUrl("pricing")];
        $urls[] = ["loc" => $this->generateUrl("blog")];
        foreach (
            $this->managerRegistry->getRepository(LegalPage::class)->findBy(["isPublished" => true])
            as $legalPage
        ) {
            $urls[] = ["loc" => $this->generateUrl("legal_page", ["slug" => $legalPage->getSlug()])];
        }
        foreach (
            $this->managerRegistry->getRepository(AreaPage::class)->findBy(["isPublished" => true])
            as $areaPage
        ) {
            $urls[] = ["loc" => $this->generateUrl("area_page", ["slug" => $areaPage->getSlug()])];
        }

        // add dynamic urls
        foreach (
            $this->managerRegistry->getRepository(Discipline::class)->findAll()
            as $discipline
        ) {
            $title = [
                "loc" => $discipline->getTitle(),
            ];
            $urls[] = [
                "loc" => $this->generateUrl("detail", [
                    "slug" => $discipline->getSlug(),
                    "id" => $discipline->getId(),
                ]),
                "title" => $title,
            ];
        }

        foreach (
            $this->managerRegistry->getRepository(Blog::class)->findAll()
            as $post
        ) {
            $url = [
                "loc" => $this->generateUrl("blog_detail", [
                    "slug" => $post->getSlug(),
                    "id" => $post->getId(),
                ]),
            ];
            if ($post->getUpdatedAt()) {
                $url["lastmod"] = $post->getUpdatedAt()->format("Y-m-d");
            }
            $urls[] = $url;
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
}
