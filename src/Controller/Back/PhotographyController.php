<?php

namespace App\Controller\Back;

use App\Entity\Photography;
use App\Form\PhotographyType;
use App\Service\FileUploader;
use App\Repository\PhotographyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/photography")
 */
class PhotographyController extends AbstractController
{
    /**
     * Borwse all the photographies, not used (just for testing)
     * 
     * @Route("/", name="photography_browse", methods={"GET"})
     */
    public function browse(PhotographyRepository $photographyRepository): Response
    {
        return $this->render('back/photography/browse.html.twig', [
            'photographies' => $photographyRepository->findAll(),
        ]);
    }

    /**
     * Add a new photography to a portfolio
     * 
     * @Route("/new", name="photography_add", methods={"GET","POST"})
     */
    public function add(Request $request, FileUploader $fileUploader): Response
    {
        $photography = new Photography();
        $form = $this->createForm(PhotographyType::class, $photography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            // Handling the picture
            $picture = $form->get('picture')->getData();
            
            // Handling the portfolio
            $portfolio = $form->get('portfolio')->getData();

            // If a picture has been send
            if ($picture) {
                $newPicture = $fileUploader->upload($picture);
                $photography->setPicture($newPicture);
            }
            
            $entityManager->persist($photography);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Ajout de la photo "' . $photography->getTitle() . '" effectué.'
            );

            return $this->redirectToRoute('portfolio_read_pictures', [
                'id' => $portfolio->getId()
            ]);
        }

        return $this->render('back/photography/add.html.twig', [
            'photography' => $photography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Read a photography's details
     * 
     * @Route("/{id}", name="photography_read", methods={"GET"})
     */
    public function show(Photography $photography): Response
    {
        return $this->render('back/photography/read.html.twig', [
            'photography' => $photography,
        ]);
    }

    /**
     * Edit a photography's details
     * 
     * @Route("/{id}/edit", name="photography_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Photography $photography, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(PhotographyType::class, $photography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Handling the picture
            $picture = $form->get('picture')->getData();

            // Handling the portfolio
            $portfolio = $form->get('portfolio')->getData();

            // If a picture has been send
            if ($picture) {
                $newPicture = $fileUploader->upload($picture);
                $photography->setPicture($newPicture);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Modification de la photo "' . $photography->getTitle() . '" effectuée.'
            );

            return $this->redirectToRoute('portfolio_read_pictures', [
                'id' => $portfolio->getId()
            ]);
        }

        return $this->render('back/photography/edit.html.twig', [
            'photography' => $photography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a photography
     * 
     * @Route("/{id}", name="photography_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Photography $photography): Response
    {
        $portfolio = $photography->getPortfolio();

        if ($this->isCsrfTokenValid('delete'.$photography->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photography);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La photo "' . $photography->getTitle() . '" a bien été supprimée.'
            );
        }        

        return $this->redirectToRoute('portfolio_read_pictures', [
            'id' => $portfolio->getId()
        ]);
    }
}
