<?php

namespace App\Controller\Back;

use App\Entity\Photography;
use App\Form\PhotographyType;
use App\Repository\PhotographyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/photography")
 */
class PhotographyController extends AbstractController
{
    /**
     * @Route("/", name="photography_browse", methods={"GET"})
     */
    public function browse(PhotographyRepository $photographyRepository): Response
    {
        return $this->render('back/photography/browse.html.twig', [
            'photographies' => $photographyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="photography_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        $photography = new Photography();
        $form = $this->createForm(PhotographyType::class, $photography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photography);
            $entityManager->flush();

            return $this->redirectToRoute('photography_browse');
        }

        return $this->render('back/photography/new.html.twig', [
            'photography' => $photography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photography_read", methods={"GET"})
     */
    public function show(Photography $photography): Response
    {
        return $this->render('back/photography/read.html.twig', [
            'photography' => $photography,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="photography_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Photography $photography): Response
    {
        $form = $this->createForm(PhotographyType::class, $photography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('photography_browse');
        }

        return $this->render('back/photography/edit.html.twig', [
            'photography' => $photography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photography_delete", methods={"POST"})
     */
    public function delete(Request $request, Photography $photography): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photography->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photography);
            $entityManager->flush();
        }

        return $this->redirectToRoute('photography_browse');
    }
}
