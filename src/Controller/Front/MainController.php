<?php

namespace App\Controller\Front;

use App\Entity\Portfolio;
use App\Repository\BiographyRepository;
use App\Repository\PortfolioRepository;
use App\Repository\PhotographyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="front_home")
     */
    public function home(PortfolioRepository $portfolioRepository): Response
    {
        return $this->render('front/main/index.html.twig', [
            'portfolios' => $portfolioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/collection/{id<\d+>}", name="front_portfolio_read")
     */
    public function portfolio(Portfolio $portfolio = null, PhotographyRepository $photographyRepository)
    {
        $photographies = $photographyRepository->findBy(['portfolio' => $portfolio], $orderBy = array ('imgOrder' => 'ASC'));
        return $this->render('front/main/collection.html.twig', [
            'portfolio' => $portfolio,
            'photographies' => $photographies,
        ]);
    }

    /**
     * @Route("/biographie", name="front_biography_read")
     */
    public function biography(BiographyRepository $biographyRepository)
    {
        return $this->render('front/main/biography.html.twig', [
            'biographies' => $biographyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/sitemap", name="front_sitemap")
     */
    public function sitemap()
    {
        return $this->render('front/sitemap/sitemap.html.twig');
    }
}
