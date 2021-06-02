<?php

namespace App\Controller\Back;

use App\Entity\Biography;
use App\Form\BiographyType;
use App\Repository\BiographyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/biography")
 */
class BiographyController extends AbstractController
{
    /**
     * Browse the biography
     * 
     * @Route("/", name="biography_browse", methods={"GET"})
     */
    public function index(BiographyRepository $biographyRepository): Response
    {
        return $this->render('back/biography/browse.html.twig', [
            'biographies' => $biographyRepository->findAll(),
        ]);
    }

    /**
     * Add a biography (only accessible if there's not already one)
     * 
     * @Route("/new", name="biography_add", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $biography = new Biography();
        $form = $this->createForm(BiographyType::class, $biography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($biography);
            $entityManager->flush();

            return $this->redirectToRoute('biography_browse');
        }

        return $this->render('back/biography/add.html.twig', [
            'biography' => $biography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modify the biography (only accessible if there's one)
     * @Route("/{id}/edit", name="biography_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Biography $biography): Response
    {
        $form = $this->createForm(BiographyType::class, $biography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('biography_browse');
        }

        return $this->render('back/biography/edit.html.twig', [
            'biography' => $biography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete the biography (only accessible if there's one)
     * @Route("/{id}", name="biography_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Biography $biography): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biography->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($biography);
            $entityManager->flush();
        }

        return $this->redirectToRoute('biography_browse');
    }
}
