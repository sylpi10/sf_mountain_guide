<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Image;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/", name="album_index", methods={"GET"})
     */
    public function index(AlbumRepository $albumRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
      

        return $this->render('album/index.html.twig', [ 
            'albums' => $albumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="album_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get the images
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                // new filename
                $file = md5(uniqid()) . '.' . $image->guessExtension();

                // copy file to uploads directory
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );

                // stock img name in db
                $img = new Image();
                $img ->setName($file);
                $album->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('album_index');
        }

        return $this->render('album/new.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="album_show", methods={"GET"})
     */
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="album_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Album $album): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                     // get the images
                     $images = $form->get('images')->getData();
                     foreach ($images as $image) {
                         // new filename
                         $file = md5(uniqid()) . '.' . $image->guessExtension();
         
                         // copy file to uploads directory
                         $image->move(
                             $this->getParameter('images_directory'),
                             $file
                         );
         
                         // stock img name in db
                         $img = new Image();
                         $img ->setName($file);
                         $album->addImage($img);
                     }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('album_index');
        }

        return $this->render('album/edit.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="album_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Album $album): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($album);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('album_index');
    }
    
    /**
     * @Route("/delete/image/{id}", name="delete_img", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        //check if token isvalid
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            
            // get image name
            $name = $image->getName();
            //del the file
            unlink($this->getParameter("images_directory").'/'.$name);
            //del from db
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();

            // json Response
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Invalid token'], 400);
        }
    }
}
