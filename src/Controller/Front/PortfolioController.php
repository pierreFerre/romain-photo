<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
    /**
     * @Route("/front/portfolio", name="front_portfolio")
     */
    public function index(): Response
    {
        return $this->render('front/portfolio/index.html.twig', [
            'controller_name' => 'PortfolioController',
        ]);
    }
}
