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
     * @Route("/", name="photography_index", methods={"GET"})
     */
    public function index(PhotographyRepository $photographyRepository): Response
    {
        return $this->render('photography/index.html.twig', [
            'photographies' => $photographyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="photography_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $photography = new Photography();
        $form = $this->createForm(PhotographyType::class, $photography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photography);
            $entityManager->flush();

            return $this->redirectToRoute('photography_index');
        }

        return $this->render('photography/new.html.twig', [
            'photography' => $photography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photography_show", methods={"GET"})
     */
    public function show(Photography $photography): Response
    {
        return $this->render('photography/show.html.twig', [
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

            return $this->redirectToRoute('photography_index');
        }

        return $this->render('photography/edit.html.twig', [
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

        return $this->redirectToRoute('photography_index');
    }
}
