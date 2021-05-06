<?php

namespace App\Controller\Back;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\PhotographyRepository;
use App\Service\FileUploader;
use App\Repository\PortfolioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PortfolioController extends AbstractController
{
    /**
     * Read all
     * @Route("/", name="portfolio_browse", methods={"GET"})
     */
    public function browse(PortfolioRepository $portfolioRepository): Response
    {        
        // dd($portfolioRepository->findAll());
        return $this->render('back/portfolio/browse.html.twig', [
            'portfolios' => $portfolioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="portfolio_add", methods={"GET","POST"})
     */
    public function add(Request $request, FileUploader $fileUploader): Response
    {
        $portfolio = new Portfolio();
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // Handling the picture
            $picture = $form->get('picture')->getData();
            // If a picture has been send
            if ($picture) {
                $newPicture = $fileUploader->upload($picture);
                $portfolio->setPicture($newPicture);
            }

            $entityManager->persist($portfolio);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Création du portfolio "' . $portfolio->getName() . '" effectuée.'
            );

            return $this->redirectToRoute('portfolio_browse');
        }

        return $this->render('back/portfolio/add.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_read", methods={"GET"})
     */
    public function read(Portfolio $portfolio = null, PhotographyRepository $photographyRepository): Response
    {
        $photographies = $photographyRepository->findBy(['portfolio' => $portfolio]);
        // dd($photographies);
        return $this->render('back/portfolio/read.html.twig', [
            'portfolio' => $portfolio,
            'photographies' => $photographies,
        ]);
    }

    /**
     * @Route("/see/{id}", name="portfolio_read_pictures", methods={"GET"})
     */
    public function readPictures(Portfolio $portfolio = null, PhotographyRepository $photographyRepository): Response
    {
        $photographies = $photographyRepository->findBy(['portfolio' => $portfolio]);
        // dd($photographies);
        return $this->render('back/portfolio/read-pictures.html.twig', [
            'portfolio' => $portfolio,
            'photographies' => $photographies,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="portfolio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Portfolio $portfolio, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Handling the picture
            $picture = $form->get('picture')->getData();
            // If a picture has been send
            if ($picture) {
                $newPicture = $fileUploader->upload($picture);
                $portfolio->setPicture($newPicture);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Modification du portfolio "' . $portfolio->getName() . '" enregistrée.'
            );

            return $this->redirectToRoute('portfolio_browse');
        }

        return $this->render('back/portfolio/edit.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_delete", methods={"POST"})
     */
    public function delete(Request $request, Portfolio $portfolio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$portfolio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($portfolio);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Suppression du portfolio "' . $portfolio->getName() . '" effectuée.'
            );
        }

        return $this->redirectToRoute('portfolio_browse');
    }
}
