<?php

namespace App\Controller;

use App\Entity\Biography;
use App\Form\BiographyType;
use App\Repository\BiographyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/biography")
 */
class BiographyController extends AbstractController
{
    /**
     * @Route("/", name="biography_index", methods={"GET"})
     */
    public function index(BiographyRepository $biographyRepository): Response
    {
        return $this->render('biography/index.html.twig', [
            'biographies' => $biographyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="biography_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('biography_index');
        }

        return $this->render('biography/new.html.twig', [
            'biography' => $biography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="biography_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Biography $biography): Response
    {
        $form = $this->createForm(BiographyType::class, $biography);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('biography_index');
        }

        return $this->render('biography/edit.html.twig', [
            'biography' => $biography,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="biography_delete", methods={"POST"})
     */
    public function delete(Request $request, Biography $biography): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biography->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($biography);
            $entityManager->flush();
        }

        return $this->redirectToRoute('biography_index');
    }
}
