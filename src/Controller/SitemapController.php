<?php

namespace App\Controller;

use App\Entity\Discipline;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request)
    {
        // get host name from url
        $hostname = $request->getSchemeAndHttpHost();

        //init url array 
        $urls = [];

        // add statics urls 
        // $urls[] = $urls->push()
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('about')];
        $urls[] = ['loc' => $this->generateUrl('login')];
        $urls[] = ['loc' => $this->generateUrl('blog')];

        // add dynamic urls 
        foreach ($this->managerRegistry->getRepository(Discipline::class)->findAll() as $discipline) {
            $title = [
                'loc' => $discipline->getTitle()
            ];
            $urls[] = [
                'loc' => $this->generateUrl('detail', [
                    'slug' => $discipline->getSlug(),
                    'id' => $discipline->getId()
                ]),
                'title' => $title,
            ];
        }
        // dd($urls);

        // create response 
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );

        // add headers 
        $response->headers->set('Content-Type', 'text/xml');

        //send response 
        return $response;
    }
}
