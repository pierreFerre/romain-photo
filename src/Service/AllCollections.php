<?php

namespace App\Service;

use App\Repository\PortfolioRepository;
use Symfony\Component\HttpKernel\KernelInterface;

class AllCollections
{
  public function __construct(PortfolioRepository $portfolioRepository)
    {
        $this->portfolioRepository = $portfolioRepository;
    }

  // Get all of the existent portfolios
  public function getCollections()
  {
    $albums = $this->portfolioRepository->findAll();

    return $albums;
  }
}